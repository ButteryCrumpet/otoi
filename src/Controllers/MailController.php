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
    private $conditionChecker;

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
        $this->conditionChecker = new ConditionCheck();
    }

    public function mail($formName)
    {
        $formName = empty($formName) ? "default" : $formName;
        $form = $this->formBox->get();
        if (!$form->isValid()) {
            $resp = new Response(303);
            return $resp->withHeader("Location", $this->buildActionUrl($formName, "?errors"));
        }

        $configs = $this->mailConfigLoader->load($formName);

        foreach ($form as $field) {
            $this->placeholderStore[$field->getName()] = $field->getValue();
        }

        $mailer = new Mailer($this->templates);
        $default = [];
        $sent = false;
        foreach ($configs as $config) {
            if (is_null($config->getCondition())) {
                $default[] = $config;
                continue;
            }
            if ($this->conditionChecker->check($config->getCondition(), $form)) {
                $sent = $sent || $mailer->send($config, $form);
            }
        }
        if (!$sent) {
            foreach ($default as $config) {
                $mailer->send($config, $form);
            }
        }
        $response = new Response(200);
        return $response->withHeader("Location", $this->buildActionUrl($formName, "thanks"));
    }

    private function buildActionUrl($formName, $action)
    {
        $action = $formName !== "default" ? "/$formName/$action" : "/$action";
        return $this->config["base-url"] . $action;
    }
}