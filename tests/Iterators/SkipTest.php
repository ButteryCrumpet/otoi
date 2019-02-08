<?php

use Otoi\Iterators\Skip;
use PHPUnit\Framework\TestCase;

class SkipTest extends TestCase
{
    public function test__construct()
    {
        $this->assertInstanceOf(
            Skip::class,
            new Skip(new ArrayIterator([]), 3)
        );
    }

    public function testCollect()
    {
        $instance = new Skip(new ArrayIterator([1, 2, 3, 4, 5]), 2);

        $this->assertEquals(
            [3, 4, 5],
            $instance->collect()
        );
    }
}
