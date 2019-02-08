<?php

namespace Otoi\Iterators;


class Map extends Iterator
{
    protected $mapFn;

    public function __construct(\Iterator $from, \Closure $map)
    {
        parent::__construct($from);
        $this->mapFn = $map;
    }

    public function current()
    {
        return call_user_func($this->mapFn, $this->from->current());
    }
}