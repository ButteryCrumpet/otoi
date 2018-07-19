<?php

namespace Otoi;

use SuperSimpleTemplates\TemplateFactory;

class View
{
    private $factory;
    private $actionTemplates;

    public function __construct(array $actionTemplates, $factory)
    {
        $this->factory = $factory;
        $this->actionTemplates = $actionTemplates;
    }

    public function render($action, $context)
    {
        if (!array_key_exists($action, $this->actionTemplates)) {
            return "No view";
        }
        $filename = $this->actionTemplates[$action];
        return $this->factory->build($filename, $context)->render();
    }
}