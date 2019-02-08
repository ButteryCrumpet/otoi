<?php

namespace Otoi\Iterators;


class ForEachIter extends Iterator
{
    protected $feFn;

    public function __construct(\Iterator $from, \Closure $map)
    {
        parent::__construct($from);
        $this->feFn = $map;
    }

    public function current()
    {
        $val = $this->from->current();
        call_user_func($this->feFn, $val);
        return $val;
    }
}