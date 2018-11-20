<?php

namespace Otoi\Controllers;

use GuzzleHttp\Psr7\Response;
use Otoi\ConditionCheck;
use Otoi\Interfaces\FormLoaderInterface;
use Otoi\Interfaces\MailConfigLoaderInterface;
use Otoi\Interfaces\TemplateInterface;
use Otoi\Interfaces\ValidationInterface;
use Otoi\Mailer;
use Otoi\Models\EmailAddress;
use Otoi\Models\Form;
use Otoi\Models\MailConfig;
use Otoi\StringPlaceholder;
use SuperSimpleFramework\Interfaces\RequestAwareInterface;
use SuperSimpleFramework\Traits\RequestAware;

class MailController implements RequestAwareInterface
{
    use RequestAware;

    private $validation;
    private $templates;
    private $formLoader;
    private $mailConfigLoader;
    private $form;

    public function __construct(
        TemplateInterface $templates,
        ValidationInterface $validator,
        FormLoaderInterface $formLoader,
        MailConfigLoaderInterface $mailConfigLoader
    ) {
        $this->templates = $templates;
        $this->validation = $validator;
        $this->formLoader = $formLoader;
        $this->mailConfigLoader = $mailConfigLoader;
    }

    public function mail($formName = "default")
    {
        $this->loadForm($formName);
        if (!$this->form->isValid()) {
            return new Response(403);
        }

        $configs = $this->mailConfigLoader->load($formName);
        $mailer = new Mailer($this->templates);
        foreach ($configs as $config) {
            if ($this->meetsConditions($config)) {
                $this->prep($config);
                $mailer->send($config, $this->form);
                var_dump("hi");
            }
        }
        $response = new Response(200);
        return $response->withHeader("Location", "/");
    }

    private function meetsConditions(MailConfig $config)
    {
        if (is_null($config->getCondition())) {
            return true;
        }
        $checker = new ConditionCheck();
        $met = $checker->check($config, $this->form);
        return $met;
    }

    private function prep(MailConfig $config)
    {
        $this->setEmailPlaceholders($config->getTo());
        $this->setEmailPlaceholders($config->getFrom());
        foreach ($config->getBcc() as $bcc) {
            $this->setEmailPlaceholders($bcc);
        }
        foreach ($config->getCc() as $cc) {
            $this->setEmailPlaceholders($cc);
        }
    }

    private function setEmailPlaceholders(EmailAddress $email)
    {
        $name = $email->getName();
        $address = $email->getAddress();
        if ($name instanceof StringPlaceholder) {
            $this->setPlaceholder($name);
        }
        if ($address instanceof StringPlaceholder) {
            $this->setPlaceholder($address);
        }

        if (!filter_var($address, FILTER_VALIDATE_EMAIL)) {
            throw new \RuntimeException("$address is not a valid email address");
        }
    }

    private function setPlaceholder(StringPlaceholder $placeholder)
    {
        $name = $placeholder->getName();
        if (!isset($this->form[$name])) {
            throw new \InvalidArgumentException("No value of $name exists for placeholder");
        }
        $value = $this->form[$name]->getValue();
        $placeholder->setValue($value);
    }

    private function loadForm($name)
    {
        $form = $this->formLoader->load($name);
        $this->validateForm($form);
        $this->form = $form;
    }

    private function validateForm(Form $form)
    {
        $values = $this->request->getParsedBody();
        foreach ($form as $field) {
            if (isset($values[$field->getName()])) {
                $field->setValue($values[$field->getName()]);
            }
            $this->validation->validate($field);
        }
        return $form->isValid();
    }
}