<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2018/11/26
 * Time: 15:53
 */

use Otoi\ConditionCheck;
use PHPUnit\Framework\TestCase;

class ConditionCheckTest extends TestCase
{

    public function testItInitialises()
    {
        $this->assertInstanceOf(
            ConditionCheck::class,
            new ConditionCheck()
        );
    }

    public function testCheck()
    {
        $equals = "hi==ho";
        $member = "hi[x]ho";

        $checker = new ConditionCheck();
        $this->assertTrue($checker->check($equals, ["hi" => "ho"]), "equals true");
        $this->assertTrue($checker->check($member, ["hi" => ["ho"]]), "member true");
        $this->assertFalse($checker->check($equals, ["hi" => "nope"]), "equals false");
        $this->assertFalse($checker->check($member, ["hi" => "nope"]), "equals true");
    }
}
