<?php

namespace Otoi\Templates;

class PHPTemplates implements TemplateInterface
{

    private $directory;
    private $helpers;

    public function __construct($dir, $helpers = array())
    {
        $this->directory = $this->normalizeDir($dir);
        $this->helpers = $helpers;
    }

    public function render($name, array $args = array())
    {
        $file = $this->directory . "/" . $this->normalizePath($name);
        if (!file_exists($file)) {
            throw new \RuntimeException(sprintf("Template with name %s does not exist", $name));
        }
        ob_start();
        extract($args);
        extract($this->helpers);
        include __DIR__ ."/prelude.php";
        require $file;

        return ob_get_clean();
    }

    private function normalizePath($path)
    {
        $path = trim($path);
        if (substr($path, -4) !== ".php") {
            $path = $path . ".php";
        }

        return ltrim($path, DIRECTORY_SEPARATOR);
    }

    private function normalizeDir($dir)
    {
        $do = new \SplFileInfo($dir);
        $realPath = $do->getRealPath();
        if (!$do->isDir()) {
            throw new \RuntimeException(sprintf(_("No directory at %s"), $realPath));
        }

        if (!$do->isReadable()) {
            throw new \RuntimeException(sprintf(_("Directory %s must be readable"), $realPath));
        }

        return $realPath;
    }

}