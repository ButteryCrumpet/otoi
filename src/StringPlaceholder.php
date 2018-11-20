<?php

namespace Otoi;

class StringPlaceholder
{
    private $name;
    private $value;

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    public function setValue($value)
    {
        if (!is_string($value)) {

            throw new \InvalidArgumentException(sprintf(
                "Value must be a string %s was given",
                gettype($value)
            ));
        }

        $this->value = $value;
    }

    public function __toString()
    {
       if (is_null($this->value)) {
           $message = sprintf(
               "StringPlaceholder of name %s has not been given a value before being displayed",
               $this->name
           );
           trigger_error($message, E_USER_ERROR);
           return '';
       }
       return $this->value;
    }
}