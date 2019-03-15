<?php

namespace Otoi\Controllers;

use Otoi\ConditionCheck;
use Otoi\Config;
use Otoi\FormBox;
use Otoi\Repositories\FormRepository;
use Otoi\Repositories\MailConfigRepository;
use Otoi\Sessions\SessionInterface;
use Otoi\Templates\TemplateInterface;
use Otoi\Mailer;
use Otoi\StringStore;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MailController
{

    private $formRepository;
    private $templates;
    private $config;
    private $mailConfigLoader;
    private $placeholderStore;
    private $conditionChecker;
    private $session;

    public function __construct(
        TemplateInterface $templates,
        Config $config,
        FormRepository $formRepository,
        MailConfigRepository $mailConfigLoader,
        SessionInterface $session,
        StringStore $placeholderStore
    ) {
        $this->templates = $templates;
        $this->config = $config;
        $this->formRepository = $formRepository;
        $this->mailConfigLoader = $mailConfigLoader;
        $this->session = $session;
        $this->placeholderStore = $placeholderStore;
        $this->conditionChecker = new ConditionCheck();
    }

    public function mail(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $formName = isset($args["form"]) ? $args["form"] : "default";
        $configs = $this->mailConfigLoader->load($formName);

        $data = (array)$request->getParsedBody();

        foreach ($data as $name => $val) {
            if (is_string($val)) {
                $this->placeholderStore[$name] = $val;
            }
        }

        $mailer = new Mailer($this->templates);
        $default = [];
        $sent = false;
        foreach ($configs as $config) {
            if (is_null($config->getCondition())) {
                $default[] = $config;
                continue;
            }
            if ($this->conditionChecker->check($config->getCondition(), $data)) {
                $sent = $sent || $mailer->send($config, $data);
            }
        }
        if (!$sent) {
            foreach ($default as $config) {
                $mailer->send($config, $data);
            }
        }

        $this->session->condemn();
        $url =  $this->buildActionUrl("default", "thanks") . "?$formName";
        return $response
            ->withStatus(303)
            ->withHeader("Location", $url);
    }

    private function buildActionUrl($formName, $action = "")
    {
        $action = $formName !== "default" ? "/$formName/$action" : "/$action";
        return $this->config["base-url"] . $action;
    }
}