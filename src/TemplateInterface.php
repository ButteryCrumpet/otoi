<?php

namespace Otoi;

interface TemplateInterface
{
    public function render($name, array $args = array());
}