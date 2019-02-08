<?php

use Otoi\Iterators\Flatten;
use PHPUnit\Framework\TestCase;

class FlattenTest extends TestCase
{

    public function test__construct()
    {
        $this->assertInstanceOf(
            Flatten::class,
            new Flatten(new RecursiveArrayIterator([]))
        );
    }

    public function testCollect()
    {
        $instance = new Flatten(new RecursiveArrayIterator([
            "hi" => 2,
            [ "hi" => ["hi" => 1, 2] ],
            ["ho"],
            "he"
        ]));

        $this->assertEquals(
            [2, 1, 2, "ho", "he"],
            $instance->collect()
        );
    }
}
