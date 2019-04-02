<?php

namespace Otoi\Templates;

interface TemplateInterface
{
    public function render($name, array $args = array());
}