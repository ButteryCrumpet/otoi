<?php

namespace Otoi\Models;

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
     * @var null|string
     */
    private $name;

    /**
     * Email constructor.
     * @param string $address
     * @param null|string $name
     */
    public function __construct($address, $name = null)
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
     * @return null|string
     */
    public function getName()
    {
        return $this->name;
    }


}