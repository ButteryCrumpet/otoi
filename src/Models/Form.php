<?php

namespace Otoi\Models;

class Form implements \ArrayAccess, \Iterator
{
    private $name;
    private $fields = array();

    public function __construct($name)
    {
        $this->name = $name;
    }

    public function isValid()
    {
        $valid = true;
        foreach ($this->fields as $field) {
            $valid = $valid && $field->isValid();
        }
        return $valid;
    }

    public function current()
    {
        return \current($this->fields);
    }

    public function next()
    {
        return \next($this->fields);
    }

    public function key()
    {
        return \key($this->fields);
    }

    public function valid()
    {
        return key($this->fields) !== null;
    }

    public function rewind()
    {
        return reset($this->fields);
    }

    public function offsetExists($offset)
    {
        return isset($this->fields[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->fields[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            throw new \InvalidArgumentException("Offset must not be null");
        }

        if (!($value instanceof Field)) {
            throw new \InvalidArgumentException(
                "Value must be an instance of " . Field::class
            );
        }

        if (is_null($offset)) {
            $offset = $value->getName();
        }

        $this->fields[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->fields[$offset]);
    }
}