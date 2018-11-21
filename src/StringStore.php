<?php

namespace Otoi;


class StringStore implements \ArrayAccess
{
    private $store = array();

    public function makePlaceholder($name)
    {
        return new StringPlaceholder($name, $this);
    }

    public function offsetExists($offset)
    {
        return isset($this->store[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->store[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            throw new \InvalidArgumentException("Offset cannot be null");
        }

        if (!is_string($value)) {
            throw new \InvalidArgumentException(
                "Value must be a string. " . gettype($value) . " was given"
            );
        }

        $this->store[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->store[$offset]);
    }

}