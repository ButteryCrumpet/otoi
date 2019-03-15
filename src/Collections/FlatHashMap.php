<?php

namespace Otoi\Collections;

class FlatHashMap implements \ArrayAccess, \IteratorAggregate
{
    use InnerArrayAccessTrait;

    private $separator;
    private $wildcard;

    public static function fromArray(array $arr)
    {
        $new = new self();
        foreach ($arr as $key => $val) {
            $new[$key] = $val;
        }
        return $new;
    }

    public function __construct($separator = ".", $wildcard = "*")
    {
        $this->separator = $separator;
        $this->wildcard = $wildcard;
    }

    public function toArray()
    {
        $arr = [];
        foreach ($this->inner as $key => $value) {
            $keys = explode($this->separator, $key);
            $this->buildArray($keys, $value, $arr);
        }
        return $arr;
    }

    public function offsetGet($offset)
    {
        if (substr_count($offset, $this->wildcard) > 0) {
            return $this->getWildcardOffset($offset);
        }

        return isset($this->inner[$offset]) ? $this->inner[$offset] : null;
    }

    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $offset = count($this->inner);
        }

        if (is_array($value) || $value instanceof \Iterator) {
            $this->setArray($value, $offset. $this->separator);
        } else {
            $this->inner[$offset] = $value;
        }
    }

    private function getWildcardOffset($offset)
    {
        $values = [];
        $regex = str_replace(".", "\.", $offset);
        $regex = str_replace($this->wildcard, "[a-zA-Z0-9]+", $regex);
        foreach ($this->inner as $key => $value) {
            if (preg_match("/^" . $regex . "$/", $key)) {
                $values[] = $value;
            }
        }
        return $values;
    }

    private function buildArray($keys, $value, &$arr)
    {
        $key = array_shift($keys);
        if (empty($keys)) {
            $arr[$key] = $value;
           return;
        }
        $arr[$key] = [];
        $this->buildArray($keys, $value, $arr[$key]);
    }

    private function setArray($arr, $prefix)
    {
        foreach ($arr as $key => $value) {
            if (is_array($value)) {
                $this->setArray($value, $prefix . $key . $this->separator);
            } else {
                $this->inner[$prefix . $key] = $value;
            }
        }
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->toArray());
    }
}