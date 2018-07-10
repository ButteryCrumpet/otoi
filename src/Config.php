<?php

namespace Otoi;

class Config implements \ArrayAccess
{
    private $allowedFileTypes = [
        "php"
    ];

    private $config = [];

    public function __construct($file)
    {
        $info = new \SplFileInfo($file);
        if (!$info->isFile()) {
            throw new \InvalidArgumentException("Not a valid file");
        }
        $extension = $info->getExtension();
        if (!in_array($extension, $this->allowedFileTypes)) {
            throw new \InvalidArgumentException(
                sprintf("%s is not an allowed file type", $extension)
            );
        }

        $this->config = $this->$extension($file);

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
            throw new \InvalidArgumentException("offset must not be null");
        }

        if ($this->offsetExists($offset)) {
            throw new \Exception("Not allowed to alter config once set");
        }

        $this->items[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        if ($this->offsetExists($offset)) {
            throw new \Exception("Not allowed to alter config once set");
        }
    }

}