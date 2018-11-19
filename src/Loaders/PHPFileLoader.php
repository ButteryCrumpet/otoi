<?php

namespace Otoi\Loaders;

use Otoi\Interfaces\LoaderInterface;
use Otoi\Models\Field;
use Otoi\Models\Form;

class PHPFileLoader implements LoaderInterface
{
    public $configDir;

    public function __construct($configDir)
    {
        $info = new \SplFileInfo($configDir);
        if (!$info->isDir()) {
            throw new \InvalidArgumentException("Config directory must be valid directory");
        }

        if (!$info->isReadable()) {
            throw new \InvalidArgumentException("Config directory must be readable");
        }

        $this->configDir = rtrim($configDir, "/");
    }

    public function load($name): Form
    {
        $path = $this->configDir . "/$name.php";
        $info = new \SplFileInfo($path);
        if (!$info->isFile() && !$info->isReadable()) {
            throw new \InvalidArgumentException("$name does not exist as a Form");
        }
        $form = new Form($name);
        $fields = require $path;
        foreach ($fields as $name => $field) {
            $form[$name] = $this->arrayToField($name, $field);
        }
        return $form;
    }

    public function all(): array
    {
        $forms = [];
        foreach ($this->list() as $form) {
            $forms[] = $this->load($form);
        }
        return $forms;
    }

    public function list(): array
    {
        $names = [];
        foreach (new \DirectoryIterator($this->configDir) as $fileInfo) {
            if($fileInfo->isDot()) continue;
            $names[] = $fileInfo->getFilename();
        }
        return $names;
    }

    private function arrayToField($name, $array)
    {
        $defaults = [
            "validation" => "",
            "type" => "text",
            "defaultValue" => "",
        ];

        $config = array_merge($defaults, $array);
        return new Field(
            $name,
            $config["type"],
            $config["defaultValue"],
            $config["validation"]
        );
    }

}