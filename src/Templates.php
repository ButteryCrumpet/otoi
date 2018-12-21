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
        $siteUrl = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"];
        $pageUrl = $siteUrl . $_SERVER["REQUEST_URI"];
        $this->twig->addGlobal("ROOT", $_SERVER["DOCUMENT_ROOT"]);
        $this->twig->addGlobal("HTTPS", !empty($_SERVER["HTTPS"]));
        $this->twig->addGlobal("HOST", $_SERVER['HTTP_HOST']);
        $this->twig->addGlobal("URI", $_SERVER["REQUEST_URI"]);
        $this->twig->addGlobal("UA", $_SERVER['HTTP_USER_AGENT']);
        $this->twig->addGlobal("SITE_URL", $siteUrl);
        $this->twig->addGlobal("PAGE_URL", $pageUrl);
        $this->twig->addGlobal("REMOTE_HOST", $_SERVER["REMOTE_ADDR"]);
        $this->twig->addGlobal("USER_AGENT", $_SERVER['HTTP_USER_AGENT']);
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