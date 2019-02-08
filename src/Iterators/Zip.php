<?php

namespace Otoi\Iterators;


class Zip extends Iterator
{
    public function __construct(\Iterator $from, \Iterator $zip)
    {
        $multi = new \MultipleIterator(\MultipleIterator::MIT_NEED_ANY);
        $multi->attachIterator($from);
        $multi->attachIterator($zip);
        parent::__construct($multi);
    }

    public function collect()
    {
        $arr = [];
        foreach ($this as $val) {
            $arr[] = $val;
        }
        return $arr;
    }
}