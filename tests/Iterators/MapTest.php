<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2019-02-08
 * Time: 13:53
 */

use Otoi\Iterators\Map;
use PHPUnit\Framework\TestCase;

class MapTest extends TestCase
{

    public function test__construct()
    {
        $this->assertInstanceOf(
            Map::class,
            new Map(new ArrayIterator(["hi"]), function($val){ return $val; })
        );
    }

    public function testCurrent()
    {
        $instance = new Map(
            new ArrayIterator([1, 2, 3]),
            function($val){ return $val * $val; }
        );

        $this->assertEquals(
            [1, 4, 9],
            $instance->collect()
        );
    }

}
