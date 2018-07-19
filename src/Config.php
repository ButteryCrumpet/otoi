<?php

namespace Otoi;

class Config implements \ArrayAccess
{
    private $allowedFileTypes = [
        "php"
    ];

    private $config = [];

    public function __construct(array $files = [])
    {
        foreach ($files as $file) {
            $this->addFile($file);
        }
    }

    public function addFile($file)
    {
        $info = new \SplFileInfo($file);
        if (!$info->isFile()) {
            throw new \InvalidArgumentException(
                sprintf("%s is not a valid file", $file)
            );
        }
        $extension = $info->getExtension();
        if (!method_exists($this, $extension)) {
            throw new \InvalidArgumentException(
                sprintf("%s is not an allowed file type", $extension)
            );
        }

        $config = $this->$extension($file);

        if (!is_array($config)) {
            throw new \InvalidArgumentException("Config must be an array");
        }

        foreach ($config as $key => $val) {
            $this->offsetSet($key, $val);
        }
    }

    private function php($file) {
        return require $file;
    }

    // Array Access implementation

    public function offsetExists($offset)
    {
        return isset($this->config[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->config[$offset]) ? $this->config[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            throw new \InvalidArgumentException("Offset must not be null");
        }

        if ($this->offsetExists($offset)) {
            throw new \InvalidArgumentException("Key '" . $offset . "' already exists");
        }

        $this->config[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            throw new \InvalidArgumentException("Not allowed to alter config once set at key $offset");
        }
    }

}