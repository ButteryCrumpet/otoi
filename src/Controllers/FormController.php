<?php

namespace Otoi\Controllers;

use GuzzleHttp\Psr7\Response;
use Otoi\Interfaces\LoaderInterface;
use Otoi\Interfaces\TemplateInterface;
use Otoi\Interfaces\ValidationInterface;
use Otoi\Models\Form;
use SuperSimpleFramework\Interfaces\RequestAwareInterface;
use SuperSimpleFramework\Traits\RequestAware;

class FormController implements RequestAwareInterface
{
    use RequestAware;

    private $validation;
    private $templates;
    private $loader;
    private $form;

    public function __construct(
        TemplateInterface $templates,
        ValidationInterface $validator,
        LoaderInterface $loader
    ) {
        $this->templates = $templates;
        $this->validation = $validator;
        $this->loader = $loader;
    }

    public function index($formName = "default")
    {
        $this->loadForm($formName);
        $params = $this->request->getQueryParams();
        $displayErrors = isset($params["errors"]);
        $body = $this->templates->render("index.twig.html", [
                "form" => $this->form,
                "displayErrors" => $displayErrors
        ]);
        return new Response(200, [], $body);
    }

    public function confirm($formName = "default")
    {
        $this->loadForm($formName);
        if (!$this->form->isValid()) {
            return new Response(403);
        }
        $body = $this->templates->render("confirm.twig.html", ["form" => $this->form]);
        return new Response(200, [], $body);
    }

    public function mail($formName = "default")
    {
       $this->loadForm($formName);
        if (!$this->form->isValid()) {
            return new Response(403);
        }

        $response = new Response(200);
        return $response->withHeader("Location", "/");
    }

    public function thanks($formName = "default")
    {
        return new Response(200, [], "thanks");
    }

    private function loadForm($name)
    {
        $form = $this->loader->load($name);
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