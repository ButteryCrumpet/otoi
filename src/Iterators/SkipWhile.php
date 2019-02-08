<?php

namespace Otoi\Iterators;


class SkipWhile extends Iterator
{
    protected $skipFn;
    protected $index = 0;

    public function __construct(\Iterator $from, \Closure $skip)
    {
        parent::__construct($from);
        $this->skipFn = $skip;
    }

    public function next()
    {
        parent::next();
        $this->index = $this->index + 1;
    }

    public function current()
    {
        while (call_user_func($this->skipFn, parent::current()) && $this->valid())
        {
            parent::next();
        }
        return parent::current();
    }

    public function key()
    {
        $key = $this->from->key();
        return is_integer($key) ? $this->index : $key;
    }
}