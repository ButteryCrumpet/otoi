<?php

use Otoi\Iterators\SkipWhile;
use PHPUnit\Framework\TestCase;

class SkipWhileTest extends TestCase
{
    public function test__construct()
    {
        $this->assertInstanceOf(
            SkipWhile::class,
            new SkipWhile(new ArrayIterator([]), function () {})
        );
    }

    public function testCollect()
    {
        $instance = new SkipWhile(
            new ArrayIterator([1, 2, 3, 4, 5]),
            function ($val) {
                return $val < 3;
            }
        );

        $this->assertEquals(
            [3, 4, 5],
            $instance->collect()
        );
    }
}
