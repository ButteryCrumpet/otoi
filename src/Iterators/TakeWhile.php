<?php

namespace Otoi\Iterators;


class TakeWhile extends Iterator
{
    protected $takeWhileFn;

    public function __construct(\Iterator $from, \Closure $takeWhile)
    {
        parent::__construct($from);
        $this->takeWhileFn = $takeWhile;
    }

    public function valid()
    {
        if (!parent::valid()) {
            return false;
        }

        return call_user_func($this->takeWhileFn, $this->current());
    }
}