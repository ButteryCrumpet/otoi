<?php

use Otoi\Iterators\Take;
use PHPUnit\Framework\TestCase;

class TakeTest extends TestCase
{
    public function test__construct()
    {
        $this->assertInstanceOf(
            Take::class,
            new Take(new ArrayIterator([]), 5)
        );
    }

    public function testCollect()
    {
        $instance = new Take(new ArrayIterator([1, 2, 3, 4, 5]), 3);

        $this->assertEquals(
            [1, 2, 3],
            $instance->collect()
        );
    }
}
