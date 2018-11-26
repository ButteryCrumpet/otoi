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
    private $label;
    /**
     * @var string
     */
    private $type;
    /**
     * @var mixed
     */
    private $defaultValue;
    /**
     * @var string
     */
    private $placeholder;
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
     * @param $name string
     * @param $label string
     * @param $type string
     * @param $placeholder string
     * @param $defaultValue mixed
     * @param $validation mixed
     */
    public function __construct(
        $name,
        $label = null,
        $type = "text",
        $placeholder = null,
        $defaultValue = null,
        $validation = null
    ) {
        $this->name = $name;
        $this->label = is_null($label) ? ucfirst($name) : $label;
        $this->type = $type;
        $this->placeholder = $placeholder;
        $this->defaultValue = $defaultValue;
        $this->validation = $validation;
    }

    /**
     * @param $value string
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
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @return string
     */
    public function getPlaceholder()
    {
        return $this->placeholder;
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
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
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