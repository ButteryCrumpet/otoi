<?php

namespace Otoi;

use Otoi\Interfaces\TemplateInterface;

class Templates implements TemplateInterface
{
    private $twig;

    public function __construct($dir, $cache, $debug)
    {
        $loader = new \Twig_Loader_Filesystem($dir);
        $loader->addPath(__DIR__ . "/templates/prelude", "prelude");
        $this->twig = new \Twig_Environment($loader, array(
            "debug" => $debug,
            'cache' => $cache,
        ));
        $this->twig->addGlobal("ROOT", $_SERVER["DOCUMENT_ROOT"]);
        $this->twig->addExtension(new \Twig_Extension_Debug());
    }

    public function render($path, array $args = array())
    {
        return $this->twig->render($path, $args);
    }

    public function enablePhpIncludes()
    {
        $function = new \Twig_Function('require_php', function ($path) {
            ob_start();
            require $path;
            return ob_get_clean();
        });
        $this->twig->addFunction($function);
    }
}