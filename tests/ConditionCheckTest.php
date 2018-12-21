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
        $field1 = $this->createMock(\Otoi\Models\Field::class);
        $field1->method("getValue")->willReturn("ho");
        $field2 = $this->createMock(\Otoi\Models\Field::class);
        $field2->method("getValue")->willReturn(["ho"]);
        $field3 = $this->createMock(\Otoi\Models\Field::class);
        $field3->method("getValue")->willReturn("nope");

        $checker = new ConditionCheck();
        $this->assertTrue($checker->check($equals, ["hi" => $field1]), "equals true");
        $this->assertTrue($checker->check($member, ["hi" => $field2]), "member true");
        $this->assertFalse($checker->check($equals, ["hi" => $field3]), "equals false");
        $this->assertFalse($checker->check($member, ["hi" => $field3]), "equals true");
    }
}
