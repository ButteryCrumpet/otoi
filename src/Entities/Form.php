<?php

namespace Otoi\Entities;

class Form implements \ArrayAccess, \Iterator
{
    private $name;
    private $fields = array();
    private $templates;
    private $finalLocation;

    public function __construct($name, FormTemplates $templates, $finalLocation = null)
    {
        $this->name = $name;
        $this->templates = $templates;
        $this->finalLocation = $finalLocation;
    }

    public function isValid()
    {
        $valid = true;
        foreach ($this->fields as $field) {
            $valid = $valid && $field->isValid();
        }
        return $valid;
    }

    public function getName()
    {
        return $this->name;
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