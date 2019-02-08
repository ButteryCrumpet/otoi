<?php

namespace Otoi\Iterators;


class Take extends Iterator
{
    protected $index = 0;

    public function __construct(\Iterator $from, $n)
    {
        parent::__construct(new \LimitIterator($from, 0, $n));
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