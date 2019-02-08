<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2019-02-08
 * Time: 14:48
 */

use Otoi\Iterators\FilterMap;
use PHPUnit\Framework\TestCase;

class FilterMapTest extends TestCase
{

    public function test__construct()
    {
        $this->assertInstanceOf(
            FilterMap::class,
            new FilterMap(new ArrayIterator([]), function(){})
        );
    }

    public function testCollect()
    {
        $instance = new FilterMap(
            new ArrayIterator([2, 3, "four", 5]),
            function ($val) {
                return is_integer($val)
                    ? $val * 2
                    : null;
            }
        );

        $this->assertEquals(
            [4 ,6, 10],
            $instance->collect()
        );
    }
}
