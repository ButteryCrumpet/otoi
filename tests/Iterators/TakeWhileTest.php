<?php

use Otoi\Iterators\TakeWhile;
use PHPUnit\Framework\TestCase;

class TakeWhileTest extends TestCase
{
    public function test__construct()
    {
        $this->assertInstanceOf(
            TakeWhile::class,
            new TakeWhile(new ArrayIterator(), function (){})
        );
    }

    public function testCollect()
    {
        $instance = new TakeWhile(
            new ArrayIterator([1, 3, 4, 5]),
            function ($val) {
                return $val < 5;
            }
        );

        $this->assertEquals(
            [1, 3, 4],
            $instance->collect()
        );
    }
}
