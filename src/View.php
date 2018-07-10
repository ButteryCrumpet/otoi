<?php

namespace Otoi;

use Prophecy\Exception\InvalidArgumentException;
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
            throw new InvalidArgumentException($action ." is not a valid action");
        }
        $filename = $this->actionTemplates[$action];
        return $this->factory->build($filename, $context)->render();
    }
}