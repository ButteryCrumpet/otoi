<?php

namespace Otoi\Loaders;


use Otoi\Entities\FormTemplates;
use Otoi\Interfaces\FormLoaderInterface;
use Otoi\Interfaces\ParserInterface;
use Otoi\Interfaces\StrategyInterface;
use Otoi\Entities\Form;

class FormLoader implements FormLoaderInterface
{
    private $strategy;
    private $parser;

    public function __construct(StrategyInterface $strategy, ParserInterface $fieldParser) {
        $this->strategy = $strategy;
        $this->parser = $fieldParser;
    }

    public function load($name): Form
    {
        $data = $this->strategy->single($name);
        $this->validateSchema($data);

        $form = new Form(
            $name,
            $this->getTemplates($data["templates"]),
            $data["final-location"] ?? null
        );
        foreach ($data["fields"] as $field) {
            $form[] = $this->parser->parse($field);
        }
        return $form;
    }

    public function all(): array
    {
        $forms = [];
        foreach ($this->list() as $form) {
            $forms[] = $this->load($form);
        }
        return $forms;
    }

    public function list(): array
    {
        return $this->strategy->list();
    }

    private function validateSchema($data)
    {
        if (!is_array($data)) {
            throw new \RuntimeException("Form config must be an array");
        }
        if (!isset($data["fields"])) {
            throw new \RuntimeException("Form config must contain fields attribute");
        }
        if (!isset($data["templates"])) {
            throw new \RuntimeException("Form config must contain templates attribute");
        }
        if (!isset($data["templates"]["index"])) {
            throw new \RuntimeException("Form template config must contain index attribute");
        }
        if (!isset($data["templates"]["confirm"])) {
            throw new \RuntimeException("Form template config must contain confirm attribute");
        }
        if (!isset($data["final-location"]) && !isset($data["templates"]["final"])) {
            throw new \RuntimeException("Form config must contain either a final-location or a templates.final");
        }
    }

    private function getTemplates($templateData) {

        return new FormTemplates(
            $templateData["index"],
            $templateData["confirm"],
            $templateData["final"] ?? null
        );
    }

}