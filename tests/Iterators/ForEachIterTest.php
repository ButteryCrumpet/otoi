<?php

use Otoi\Iterators\ForEachIter;
use PHPUnit\Framework\TestCase;

class ForEachIterTest extends TestCase
{
    public function test__construct()
    {
        $this->assertInstanceOf(
            ForEachIter::class,
            new ForEachIter(new ArrayIterator(["hi"]), function($val){})
        );
    }

    public function testCurrent()
    {
        $testData = ["hi", "ho", "he"];

        $mock = $this->createMock(ForEachFnTest::class);
        $mock
            ->expects($this->exactly(3))
            ->method("test")
            ->willReturnCallback(function ($val) use ($testData) {
                $this->assertTrue(in_array($val, $testData));
            });

        $instance = new ForEachIter(
            new ArrayIterator($testData),
            function($val) use ($mock) { $mock->test($val); }
        );

        $this->assertEquals(
            $testData,
            $instance->collect(),
            "Does not alter values"
        );
    }
}

class ForEachFnTest
{
    public function test(){}
}

