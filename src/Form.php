<?php

namespace Otoi;

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
     * @var string
     */
    private $templateIndex;
    /**
     * @var string
     */
    private $templateConfirm;
    /**
     * @var null
     */
    private $finalLocation;

    /**
     * Form constructor.
     * @param string $name
     * @param string $templateIndex
     * @param string $templateConfirm
     * @param string $finalLocation
     */
    public function __construct($name, $templateIndex, $templateConfirm, $finalLocation)
    {
        $this->name = $name;
        $this->templateIndex = $templateIndex;
        $this->templateConfirm = $templateConfirm;
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
     * @return string
     */
    public function getTemplateIndex()
    {
        return $this->templateIndex;
    }

    /**
     * @return string
     */
    public function getTemplateConfirm()
    {
        return $this->templateConfirm;
    }

    /**
     * @return string
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