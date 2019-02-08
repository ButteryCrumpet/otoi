<?php

use Otoi\Iterators\Zip;
use PHPUnit\Framework\TestCase;

class ZipTest extends TestCase
{

    public function test__construct()
    {
        $this->assertInstanceOf(
            Zip::class,
            new Zip(new ArrayIterator(["hi"]), new ArrayIterator(["ho"]))
        );
    }

    public function testNext()
    {
        $iter1 = new ArrayIterator(["hi", "ho"]);
        $iter2 = new ArrayIterator(["he"]);
        $instance = new Zip($iter1, $iter2);

        $this->assertEquals(
            [['hi', 'he'], ['ho', null]],
            $instance->collect()
        );

        $instance = new Zip($iter2, $iter1);
        $this->assertEquals(
            [['he', 'hi'], [null, 'ho']],
            $instance->collect()
        );
    }
}
