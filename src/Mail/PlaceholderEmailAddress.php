<?php

namespace Otoi\Mail;


class PlaceholderEmailAddress extends EmailAddress implements PlaceholderInterface
{
    const PLCHLDR_ADDR = 1;
    const PLCHLDR_NAME = 2;
    const PLCHLDR_BOTH = 3;

    private $mode;

    public function __construct($address, $name = "", $mode = 3)
    {
        $this->address = $address;
        $this->name = $name;
        $this->mode = $mode;
    }

    /**
     * @param array $data
     * @throws \Exception
     */
    public function resolve($data)
    {
        if ($this->mode === static::PLCHLDR_ADDR || $this->mode === static::PLCHLDR_BOTH) {
            $this->address = isset($data[$this->address]) ? $data[$this->address] : "";
        }

        if ($this->mode === static::PLCHLDR_NAME || $this->mode === static::PLCHLDR_BOTH) {
            $this->name = isset($data[$this->name]) ? $data[$this->name] : "";
        }

        $this->validate();
    }
}