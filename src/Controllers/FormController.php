<?php

namespace Otoi\Controllers;

use GuzzleHttp\Psr7\Response;
use Otoi\Config;
use Otoi\FormBox;
use Otoi\Interfaces\MailConfigLoaderInterface;
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

    public function index($formName = "default")
    {
        $form = $this->formBox->get();
        $params = $this->request->getQueryParams();
        $displayErrors = isset($params["errors"]);
        $body = $this->templates->render("$formName/index.twig.html", [
                "form" => $form,
                "displayErrors" => $displayErrors
        ]);
        return new Response(200, [], $body);
    }

    public function confirm($formName = "default")
    {
        $form = $this->formBox->get();
        if (!$form->isValid()) {
            $resp = new Response(303);
            $base = $this->config["base-url"];
            return $resp->withHeader("Location", "$base?errors");
        }
        $body = $this->templates->render("$formName/confirm.twig.html", ["form" => $this->form]);
        return new Response(200, [], $body);
    }

    public function thanks($formName = "default")
    {
        $body = $this->templates->render("$formName/confirm.twig.html");
        return new Response(200, [], $body);
    }
}