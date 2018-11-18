<?php

namespace Otoi\Controllers;

use GuzzleHttp\Psr7\Response;
use Otoi\TemplateInterface;
use Otoi\Validation;

class Form
{
    private $validation;
    private $templates;

    public function __construct(Validation $validation, TemplateInterface $templates)
    {
        $this->validation = $validation;
        $this->templates = $templates;
    }

    public function display($formName = "")
    {
        $errors = $this->validation->getErrors();
        $body = $this->templates->render("display", $errors);
        return new Response(200, [], $body);
    }

    public function confirm($formName = "")
    {
        $errors = $this->validation->getErrors();
        $body = $this->templates->render("confirm.twig.html", $errors);
        return new Response(200, [], $body);
    }

    public function thanks($formName = "")
    {
        var_dump("thanks");
        return new Response();
    }
}