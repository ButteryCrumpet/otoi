<?php

namespace Otoi\Iterators;


class Filter extends Iterator
{
    protected $index = 0;

    public function __construct(\Iterator $from, \Closure $filter)
    {
        parent::__construct(new \CallbackFilterIterator($from, $filter));
    }

    public function next()
    {
        parent::next();
        $this->index = $this->index + 1;
    }

    public function key()
    {
        $key = $this->from->key();
        return is_integer($key) ? $this->index : $key;
    }
}