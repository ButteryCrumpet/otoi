<?php

namespace Otoi\Interfaces;

interface TemplateInterface
{
    public function render($name, array $args = array());
}