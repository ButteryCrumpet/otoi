<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2019-02-08
 * Time: 11:56
 */

use Otoi\Iterators\StepBy;
use PHPUnit\Framework\TestCase;

class StepByTest extends TestCase
{

    public function test__construct()
    {
        $this->assertInstanceOf(
            StepBy::class,
            new StepBy(new ArrayIterator(["hi"]), 2)
        );
    }

    public function testNext()
    {
        $iter = new ArrayIterator(["hi", "hy", "he", "ho", "ha" => "hu"]);
        $instance = new StepBy($iter, 2);

        $this->assertEquals(
            ["hi", "he", "ha" => "hu"],
            $instance->collect()
        );
    }
}
