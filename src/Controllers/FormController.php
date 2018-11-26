<?php

namespace Otoi\Controllers;

use GuzzleHttp\Psr7\Response;
use Otoi\Config;
use Otoi\FormBox;
use Otoi\Interfaces\TemplateInterface;
use SuperSimpleFramework\Interfaces\RequestAwareInterface;
use SuperSimpleFramework\Traits\RequestAware;

class FormController implements RequestAwareInterface
{
    use RequestAware;

    private $templates;
    private $formBox;
    private $config;

    public function __construct(
        TemplateInterface $templates,
        FormBox $formBox,
        Config $config
    ) {
        $this->templates = $templates;
        $this->formBox = $formBox;
        $this->config = $config;
    }

    public function index($formName)
    {
        $formName = empty($formName) ? "default" : $formName;
        $form = $this->formBox->get();
        $params = $this->request->getQueryParams();
        $displayErrors = isset($params["errors"]);
        $body = $this->templates->render("$formName/index.twig.html", [
            "action" => $this->buildActionUrl($formName, "confirm"),
            "form" => $form,
            "displayErrors" => $displayErrors
        ]);
        return new Response(200, [], $body);
    }

    public function confirm($formName = "default")
    {
        $formName = empty($formName) ? "default" : $formName;
        $form = $this->formBox->get();
        if (!$form->isValid()) {
            $resp = new Response(303);
            $url = $this->buildActionUrl($formName, "?errors");
            return $resp->withHeader("Location", $url);
        }
        $body = $this->templates->render("$formName/confirm.twig.html", [
            "action" => $this->buildActionUrl($formName, "mail"),
            "form" => $form
        ]);
        return new Response(200, [], $body);
    }

    public function thanks($formName = "default")
    {
        $formName = empty($formName) ? "default" : $formName;
        $body = $this->templates->render("$formName/thanks.twig.html");
        return new Response(200, [], $body);
    }

    private function buildActionUrl($formName, $action)
    {
        $action = $formName !== "default" ? "/$formName/$action" : "/$action";
        return $this->config["base-url"] . $action;
    }
}