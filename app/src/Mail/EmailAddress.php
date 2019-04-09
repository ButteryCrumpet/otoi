<?php

namespace Otoi\Mail;

/**
 * Class Email
 * @package Otoi\Models
 */
class EmailAddress
{
    /**
     * @var string
     */
    protected $address;
    /**
     * @var string
     */
    protected $name;

    /**
     * Email constructor.
     * @param string $address
     * @param string $name
     * @throws \Exception
     */
    public function __construct($address, $name = "")
    {
        $this->address = $address;
        $this->name = $name;
        $this->validate();
    }

    /**
     * @return string
     */
    public function addr()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function name()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return empty($this->name)
            ? $this->address
            : "{$this->name} <{$this->address}>";
    }

    protected function validate()
    {
        if (!(filter_var($this->address, FILTER_VALIDATE_EMAIL) && is_string($this->name))) {
            throw new \Exception(sprintf(
                _("Invalid email address or name. Address: %s, Name: %s"),
                $this->address,
                $this->name
            ));
        }
    }


}