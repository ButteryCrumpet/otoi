<?php

namespace Otoi\Drivers;

class PHPFileDriver implements DriverInterface
{
    /**
     * @var \SplFileInfo
     */
    private $file;

    /**
     * @var array
     */
    private $cache = [];

    /**
     * PHPFileDriver constructor.
     * @param string $file
     */
    public function __construct($file)
    {
        $info = new \SplFileInfo($file);
        if (!$info->isFile()) {
            throw new \InvalidArgumentException(
                sprintf(_("%s is not a valid file"), $info->getRealPath()));
        }

        if (!$info->isReadable()) {
            throw new \InvalidArgumentException(
                sprintf(_("%s must be readable"), $info->getRealPath()));
        }

        $this->file = $info;
    }

    /**
     * @param string $name
     * @return mixed|null
     * @throws \RuntimeException
     */
    public function single($name)
    {
        $data = $this->getData();
        if (!is_array($data)) {
            throw new \RuntimeException(
                sprintf(_("Config file must return an array. %s was returned"), gettype($data)));
        }
        return isset($data[$name]) ? $data[$name] : null;
    }

    /**
     * @return array
     * @throws \RuntimeException
     */
    public function listing()
    {
        $data = $this->getData();
        if (!is_array($data)) {
            throw new \RuntimeException(
                sprintf(_("Config file must return an array. %s was returned"), gettype($data)));
        }
        return array_keys($data);
    }

    private function getData()
    {
        if (empty($this->cache)) {
            $this->cache = require $this->file->getRealPath();
        }
        return  $this->cache;
    }
}