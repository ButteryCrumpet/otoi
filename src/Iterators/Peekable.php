<?php

namespace Otoi\Iterators;


class Peekable extends Iterator
{
    protected $window = [null, null];
    protected $end = false;

    public function __construct(\Iterator $from)
    {
        parent::__construct($from);
        $this->next();
    }

    public function peek()
    {
        return $this->window[1];
    }

    public function next()
    {
        $this->window[0] = parent::current();
        parent::next();
        if (!parent::valid()) {
            $this->window[1] = null;
        } else {
            $this->window[1] = parent::current();
        }
    }

    public function current()
    {
        return $this->window[0];
    }

    public function valid()
    {
        if (parent::valid()) {
            return true;
        }

        if (!parent::valid() && !$this->end) {
            $this->end = true;
            return true;
        }

        return false;
    }
}