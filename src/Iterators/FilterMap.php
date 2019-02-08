<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2019-02-08
 * Time: 14:44
 */

namespace Otoi\Iterators;


class FilterMap extends Iterator
{
    public function __construct(\Iterator $from, \Closure $fn)
    {
        $filter = new Filter(new Map($from, $fn), function ($val) {
             return !is_null($val);
        });
        parent::__construct($filter);
    }
}