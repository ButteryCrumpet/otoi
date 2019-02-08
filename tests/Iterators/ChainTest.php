<?php

use Otoi\Iterators\Chain;
use PHPUnit\Framework\TestCase;

class ChainTest extends TestCase
{
    public function test__construct()
    {
        $this->assertInstanceOf(
            Chain::class,
            new Chain(new ArrayIterator(["hi"]), new ArrayIterator(["ho"]))
        );
    }

    public function testNext()
    {
        $instance = new Chain(new ArrayIterator(["hi"]), new ArrayIterator(["ho"]));

        $this->assertEquals(
            [[0,"hi"], [0,"ho"]],
            $instance->collect()
        );
    }
}
