<?php

namespace Otoi;

class StringPlaceholder
{
    private $store;
    private $name;

    public function __construct($name, StringStore $store)
    {
        $this->name = $name;
        $this->store = $store;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getStore()
    {
        return $this->store;
    }

    public function __toString()
    {
       if (!isset($this->store[$this->name])) {
           $message = sprintf(
               "StringStore does not contain value for name %s",
               $this->name
           );
           trigger_error($message, E_USER_ERROR);
           return '';
       }
       return $this->store[$this->name];
    }
}