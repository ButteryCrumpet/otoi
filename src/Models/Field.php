<?php

namespace Otoi\Models;


use Otoi\Interfaces\ErrorAwareInterface;
use Otoi\Interfaces\ValidatableInterface;

/**
 * Class Field
 * @package Otoi\Models
 */
class Field implements ValidatableInterface, ErrorAwareInterface
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var string
     */
    private $type;
    /**
     * @var mixed
     */
    private $defaultValue;
    /**
     * @var mixed
     */
    private $validation;
    /**
     * @var string
     */
    private $value = "";
    /**
     * @var bool
     */
    private $valid = false;
    /**
     * @var array
     */
    private $errors = array();

    /**
     * Field constructor.
     * @param $name
     * @param $type
     * @param $defaultValue
     * @param $validation
     */
    public function __construct(
        $name,
        $type,
        $defaultValue,
        $validation
    ) {
        $this->name = $name;
        $this->type = $type;
        $this->defaultValue = $defaultValue;
        $this->validation = $validation;
    }

    /**
     * @param $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @return string | array
     */
    public function getDefaultValue()
    {
        return $this->defaultValue;
    }

    /**
     * @return string
     */
    public function getValidation()
    {
        return $this->validation;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     *
     */
    public function setValid()
    {
        $this->valid = true;
    }

    /**
     * @return bool
     */
    public function isValid()
    {
        return $this->valid;
    }

    public function setErrors($errors)
    {
        $this->errors = $errors;
    }
}