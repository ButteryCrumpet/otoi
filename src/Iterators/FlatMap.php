<?php

namespace Otoi\Iterators;


class FlatMap extends Iterator
{
    protected $mapFn;
    protected $index = 0;

    public function __construct(\Iterator $from, \Closure $map)
    {
        parent::__construct(new \RecursiveIteratorIterator($from));
        $this->mapFn = $map;
    }

    public function next()
    {
        parent::next();
        $this->index = $this->index + 1;
    }

    public function current()
    {
        return call_user_func($this->mapFn, parent::current());
    }

    public function key()
    {
        return $this->index;
    }
}