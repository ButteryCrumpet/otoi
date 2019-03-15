<?php

use Otoi\Collections\FlatHashMap;
use PHPUnit\Framework\TestCase;

class FlatHashMapTest extends TestCase
{
    public function test__construct()
    {
        $this->assertInstanceOf(
            FlatHashMap::class,
            new FlatHashMap()
        );
    }

    public function testOffsetSet()
    {
        $instance = new FlatHashMap();
        $instance["hi"] = 0;
        $instance["ho"] = ["he" => 1, 2];
        $instance[] = 3;
        $instance[] = ["what" => [4, 5], 6];

        $expected = [
            "hi" => 0,
            "ho.he" => 1,
            "ho.0" => 2,
            3 => 3,
            "4.0" => 6,
            "4.what.0" => 4,
            "4.what.1" => 5,
            "ho.*" => [1, 2],
            "4.what.*" => [4, 5],
            "*.0" => [2, 6]
        ];

        foreach ($expected as $key => $value) {
            $this->assertEquals($value, $instance[$key], "$key is not right");
        }

        $this->assertEquals(
            [
                "hi" => 0,
                "ho" => ["he" => 1, 2],
                3,
                ["what" => [4, 5], 6]
            ],
            $instance->toArray(),
            "make array"
        );
    }
}
