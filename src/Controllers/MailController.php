<?php

namespace Otoi\Controllers;

use GuzzleHttp\Psr7\Response;
use Otoi\ConditionCheck;
use Otoi\Interfaces\FormLoaderInterface;
use Otoi\Interfaces\MailConfigLoaderInterface;
use Otoi\Interfaces\TemplateInterface;
use Otoi\Interfaces\ValidationInterface;
use Otoi\Mailer;
use Otoi\Models\Form;
use Otoi\Models\MailConfig;
use Otoi\StringStore;
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
    private $placeholderStore;

    public function __construct(
        TemplateInterface $templates,
        ValidationInterface $validator,
        FormLoaderInterface $formLoader,
        MailConfigLoaderInterface $mailConfigLoader,
        StringStore $placeholderStore
    ) {
        $this->templates = $templates;
        $this->validation = $validator;
        $this->formLoader = $formLoader;
        $this->mailConfigLoader = $mailConfigLoader;
        $this->placeholderStore = $placeholderStore;
    }

    public function mail($formName = "default")
    {
        $this->loadForm($formName);
        if (!$this->form->isValid()) {
            return new Response(403);
        }

        $configs = $this->mailConfigLoader->load($formName);

        foreach ($this->form as $field) {
            $this->placeholderStore[$field->getName()] = $field->getValue();
        }

        $mailer = new Mailer($this->templates);
        foreach ($configs as $config) {
            if ($this->meetsConditions($config)) {
                $sent = $mailer->send($config, $this->form);
                var_dump($sent);
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