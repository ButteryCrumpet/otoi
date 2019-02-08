<?php

namespace Otoi\Iterators;


interface IteratorInterface extends \RecursiveIterator, \Countable
{
    public function count();

    public function last();

    public function nth($n);

    public function step_by($step);

    public function chain(\Iterator $iterator);

    public function zip(\Iterator $iterator);

    public function map(\Closure $fn);

    public function for_each(\Closure $fn);

    public function filter(\Closure $fn);

    public function filter_map(\Closure $fn);

    public function enumerate();

    public function skip_while(\Closure $fn);

    public function take_while(\Closure $fn);

    public function skip($n);

    public function take($n);

    //public function scan(); // necessary implementation?

    public function flat_map(\Closure $fn);

    public function flatten();

    public function collect();

    public function fold(\Closure $fn, $init);

    public function peekable();

    public function reverse();

}