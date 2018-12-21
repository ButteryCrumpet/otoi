<?php

namespace Otoi\Entities;

/**
 * Class Email
 * @package Otoi\Models
 */
class EmailAddress
{
    /**
     * @var string
     */
    private $address;
    /**
     * @var string
     */
    private $name;

    /**
     * Email constructor.
     * @param string $address
     * @param string $name
     */
    public function __construct($address, $name = "")
    {
        $this->address = $address;
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }


}