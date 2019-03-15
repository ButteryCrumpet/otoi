<?php

namespace Otoi\Entities;

class Form implements \ArrayAccess, \Iterator
{
    /**
     * @var string
     */
    private $name;
    /**
     * @var array
     */
    private $rules = array();
    /**
     * @var FormTemplates
     */
    private $templates;
    /**
     * @var null
     */
    private $finalLocation;

    /**
     * Form constructor.
     * @param string $name
     * @param FormTemplates $templates
     * @param null $finalLocation
     */
    public function __construct($name, FormTemplates $templates, $finalLocation = null)
    {
        $this->name = $name;
        $this->templates = $templates;
        $this->finalLocation = $finalLocation;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getRules()
    {
        return $this->rules;
    }

    /**
     * @return FormTemplates
     */
    public function getTemplates()
    {
        return $this->templates;
    }

    /**
     * @return string|null
     */
    public function getFinal()
    {
        return $this->finalLocation;
    }

    public function current()
    {
        return \current($this->rules);
    }

    public function next()
    {
        return \next($this->rules);
    }

    public function key()
    {
        return \key($this->rules);
    }

    public function valid()
    {
        return key($this->rules) !== null;
    }

    public function rewind()
    {
        return reset($this->rules);
    }

    public function offsetExists($offset)
    {
        return isset($this->rules[$offset]);
    }

    public function offsetGet($offset)
    {
        return $this->offsetExists($offset) ? $this->rules[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            throw new \InvalidArgumentException("Key must be a valid string");
        }

        $this->rules[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        unset($this->rules[$offset]);
    }
}