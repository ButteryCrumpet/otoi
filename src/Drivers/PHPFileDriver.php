<?php

namespace Otoi\Drivers;

class PHPFileDriver implements DriverInterface
{
    public $configDir;

    public function __construct($configDir)
    {
        $info = new \SplFileInfo($configDir);
        if (!$info->isDir()) {
            throw new \InvalidArgumentException("$configDir is not a valid directory");
        }

        if (!$info->isReadable()) {
            throw new \InvalidArgumentException("$configDir must be readable");
        }

        $this->configDir = rtrim($configDir, "/");
    }

    public function single($name)
    {
        $path = $this->configDir . "/$name.php";
        $info = new \SplFileInfo($path);
        if (!$info->isFile() && !$info->isReadable()) {
            return null;
        }
        return require $path;
    }

    public function listing()
    {
        $names = [];
        foreach (new \DirectoryIterator($this->configDir) as $fileInfo) {
            if($fileInfo->isDot()) continue;
            $names[] = str_replace(".php", "", $fileInfo->getFilename());
        }
        return $names;
    }
}