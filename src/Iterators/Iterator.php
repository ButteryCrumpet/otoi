<?php

namespace Otoi\Iterators;


class Iterator implements IteratorInterface
{
    protected $from;

    public static function fromArray(array $array)
    {
        return new Iterator(new \ArrayIterator($array));
    }

    public function __construct(\Iterator $from)
    {
        $this->from = $from;
    }

    public function current()
    {
        return $this->from->current();
    }

    public function next()
    {
        $this->from->next();
    }

    public function key()
    {
        return $this->from->key();
    }

    public function valid()
    {
        return $this->from->valid();
    }

    public function rewind()
    {
        $this->from->rewind();
    }

    public function getChildren()
    {
        $current = $this->current();
        return is_array($current)
            ? new \RecursiveArrayIterator($current)
            : $current;
    }

    public function hasChildren()
    {
        $current = $this->current();
        return is_array($current) || ($current instanceof \RecursiveIterator);
    }

    public function fold(\Closure $fn, $init)
    {
        $acc = $init;
        foreach ($this as $item)
        {
            $acc = call_user_func($fn, $acc, $item);
        }
        return $acc;
    }

    public function count()
    {
        $this->rewind();
        $count = 0;
        while ($this->valid()) {
            $count = $count + 1;
            $this->next();
        }
        return $count;
    }

    public function last()
    {
        $this->rewind();
        while ($this->valid()) {
            $this->next();
        }
        return $this->current();
    }

    public function nth($n)
    {
        $this->rewind();
        $count = 0;
        while ($count !== $n && $this->valid()) {
            $count = $count + 1;
            $this->next();
        }
        return $this->current();
    }

    public function step_by($step)
    {
        return new StepBy($this, $step);
    }

    public function chain(\Iterator $iterator)
    {
        return new Chain($this, $iterator);
    }

    public function zip(\Iterator $iterator)
    {
        return new Zip($this, $iterator);
    }

    public function map(\Closure $fn)
    {
        return new Map($this, $fn);
    }

    public function for_each(\Closure $fn)
    {
        return new ForEachIter($this, $fn);
    }

    public function filter(\Closure $fn)
    {
        return new Filter($this, $fn);
    }

    public function filter_map(\Closure $fn)
    {
        return new FilterMap($this, $fn);
    }

    public function enumerate()
    {
        return new Enumerate($this);
    }

    public function skip_while(\Closure $fn)
    {
        return new SkipWhile($this, $fn);
    }

    public function take_while(\Closure $fn)
    {
        return new TakeWhile($this, $fn);
    }

    public function skip($n)
    {
        return new Skip($this, $n);
    }

    public function take($n)
    {
        return new Take($this, $n);
    }

    public function flatten()
    {
        return new Flatten($this);
    }

    public function flat_map(\Closure $fn)
    {
        return new FlatMap($this, $fn);
    }

    public function reverse()
    {
        // TODO: Implement reverse() method.
    }

    public function peekable()
    {
        return new Peekable($this);
    }

    public function collect()
    {
        $arr = [];
        foreach ($this as $key => $val) {
            $arr[$key] = $val;
        }
        return $arr;
    }
}