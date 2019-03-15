<?php

namespace Otoi\Collections;


trait InnerArrayAccessTrait
{
    protected $inner = [];

    public function offsetExists($offset)
    {
        return isset($this->inner[$offset]);
    }

    public function offsetGet($offset)
    {
        return isset($this->inner[$offset]) ? $this->inner[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->inner[] = $value;
        } else {
            $this->inner[$offset] = $value;
        }
    }

    public function offsetUnset($offset)
    {
        unset($this->inner[$offset]);
    }
}