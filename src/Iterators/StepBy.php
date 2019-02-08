<?php

namespace Otoi\Iterators;


class StepBy extends Iterator
{
    protected $step;
    protected $index = 0;

    public function __construct(\Iterator $from, $step)
    {
        parent::__construct($from);
        $this->step = $step;
    }

    public function next()
    {
        for ($i = 0; $i < $this->step; $i++) {
            $this->from->next();
        }
        $this->index = $this->index + 1;
    }

    public function key()
    {
        $key = $this->from->key();
        return is_integer($key) ? $this->index : $key;
    }
}