<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2019-02-08
 * Time: 17:36
 */

use Otoi\Iterators\Peekable;
use PHPUnit\Framework\TestCase;

class PeekableTest extends TestCase
{

    public function test__construct()
    {
        $this->assertInstanceOf(
            Peekable::class,
            new Peekable(new ArrayIterator())
        );
    }

    public function testPeek()
    {
        $testData = [1, 2, 3, 4];
        $instance = new Peekable(new ArrayIterator($testData));

        $i = 0;
        $collected = [];
        while ($instance->valid()) {
            $this->assertEquals($testData[$i], $instance->current());
            $collected[] = $instance->current();
            $this->assertEquals($testData[$i+1] ?? null, $instance->peek());
            $instance->next();
            $i++;
        }

        $this->assertEquals($testData, $collected);
    }
}
