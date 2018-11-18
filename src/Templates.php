<?php

namespace Otoi;

class Templates implements TemplateInterface
{
    private $twig;

    public function __construct()
    {
        $loader = new \Twig_Loader_Filesystem(dirname(__FILE__) . '/templates');
        $this->twig = new \Twig_Environment($loader, array(
            'cache' => dirname(__FILE__) . '/cache',
        ));
    }

    public function render($path, array $args = array())
    {
        $this->twig->render($path, $args);
    }
}