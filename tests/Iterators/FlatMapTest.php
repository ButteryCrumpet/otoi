<?php

use Otoi\Iterators\FlatMap;
use PHPUnit\Framework\TestCase;

class FlatMapTest extends TestCase
{

    public function test__construct()
    {
        $this->assertInstanceOf(
            FlatMap::class,
            new FlatMap(new RecursiveArrayIterator([]), function () {})
        );
    }

    public function testCurrent()
    {
        $testData = [
            1,
            2 => [3, 4],
            "hi" => 5
        ];
        $instance = new FlatMap(new RecursiveArrayIterator($testData), function ($val) {
            return $val * 2;
        });

        $this->assertEquals(
            [2, 6, 8, 10],
            $instance->collect()
        );
    }
}
