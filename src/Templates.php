<?php

namespace Otoi;

use Otoi\Interfaces\TemplateInterface;

class Templates implements TemplateInterface
{
    private $twig;

    public function __construct()
    {
        $loader = new \Twig_Loader_Filesystem(dirname(__FILE__) . '/templates');
        $this->twig = new \Twig_Environment($loader, array(
            "debug" => true,
            'cache' => dirname(__FILE__) . '/cache',
        ));
        $this->twig->addExtension(new \Twig_Extension_Debug());
    }

    public function render($path, array $args = array())
    {
        return $this->twig->render($path, $args);
    }
}