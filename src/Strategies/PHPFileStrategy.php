<?php

namespace Otoi\Strategies;

use Otoi\Interfaces\StrategyInterface;

class PHPFileStrategy implements StrategyInterface
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

    public function single($name)
    {
        $path = $this->configDir . "/$name.php";
        $info = new \SplFileInfo($path);
        if (!$info->isFile() && !$info->isReadable()) {
            throw new \DomainException("$name does not exist as a Form");
        }
        return require $path;
    }

    public function list(): array
    {
        $names = [];
        foreach (new \DirectoryIterator($this->configDir) as $fileInfo) {
            if($fileInfo->isDot()) continue;
            $names[] = str_replace(".php", "", $fileInfo->getFilename());
        }
        return $names;
    }
}