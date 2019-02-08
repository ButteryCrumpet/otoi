<?php

use Otoi\Iterators\Enumerate;
use PHPUnit\Framework\TestCase;

class EnumerateTest extends TestCase
{
    public function test__construct()
    {
        $this->assertInstanceOf(
            Enumerate::class,
            new Enumerate(new ArrayIterator([]))
        );
    }

    public function testCollect()
    {
        $instance = new Enumerate(new ArrayIterator(["hi", "ho", "he", "ha"]));

        $this->assertEquals(
            [[0, "hi"], [1, "ho"], [2, "he"], [3, "ha"]],
            $instance->collect()
        );
    }
}
