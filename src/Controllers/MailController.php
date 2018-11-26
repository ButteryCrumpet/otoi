<?php

namespace Otoi\Controllers;

use GuzzleHttp\Psr7\Response;
use Otoi\ConditionCheck;
use Otoi\Config;
use Otoi\FormBox;
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

    private $formBox;
    private $templates;
    private $config;
    private $mailConfigLoader;
    private $form;
    private $placeholderStore;

    public function __construct(
        TemplateInterface $templates,
        FormBox $formBox,
        Config $config,
        MailConfigLoaderInterface $mailConfigLoader,
        StringStore $placeholderStore
    ) {
        $this->templates = $templates;
        $this->formBox = $formBox;
        $this->config = $config;
        $this->mailConfigLoader = $mailConfigLoader;
        $this->placeholderStore = $placeholderStore;
    }

    public function mail($formName = "default")
    {
        $form = $this->formBox->get();
        if (!$form->isValid()) {
            $resp = new Response(303);
            $base = $this->config["base-url"];
            return $resp->withHeader("Location", "$base?errors");
        }

        $configs = $this->mailConfigLoader->load($formName);

        foreach ($form as $field) {
            $this->placeholderStore[$field->getName()] = $field->getValue();
        }

        $mailer = new Mailer($this->templates);
        foreach ($configs as $config) {
            if ($this->meetsConditions($config)) {
                $sent = $mailer->send($config, $form);
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
}