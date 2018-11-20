<?php

namespace Otoi\Loaders;


use Otoi\Interfaces\FormLoaderInterface;
use Otoi\Interfaces\ParserInterface;
use Otoi\Interfaces\StrategyInterface;
use Otoi\Models\Form;

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
        $fields = $this->strategy->single($name);
        $form = new Form($name);
        foreach ($fields as $field) {
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

}