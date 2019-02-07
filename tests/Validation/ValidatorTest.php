<?php

use Otoi\Validation\Validator;
use PHPUnit\Framework\TestCase;

class ValidatorTest extends TestCase
{

    public function test__construct()
    {
        $parser = $this->createMock(\Otoi\Parsers\StringValidationParser::class);
        $this->assertInstanceOf(
            Validator::class,
            new Validator($parser)
        );
    }

    public function testValidate()
    {
        $validateable = $this->createMock(\Otoi\Validation\ValidatableInterface::class);
        $validateable->method("data")
            ->willReturn([
                "name" => [
                    "first" => "firstname",
                    "last" => "lastname"
                ],
                "email" => "email@test.com",
                "superfluous" => "meh"
            ]);
        $validateable->method("rules")
            ->willReturn([
                "name" => [
                    "first" => "required",
                    "last" => "required"
                ],
                "email" => "email"
            ]);

        $validator = $this->createMock(\SuperSimpleValidation\RuleInterface::class);
        $validator->method("validate")
            ->willReturn(true, true, true, true, false, false);
        $validator->method("getErrorMessages")
            ->willReturn(["required"], ["email"]);

        $parser = $this->createMock(\Otoi\Parsers\StringValidationParser::class);
        $parser->method("parse")
            ->willReturn($validator);

        $instance = new Validator($parser);

        $result = $instance->validate($validateable);
        $this->assertTrue($result->passed(), "returns passing result");

        $result2 = $instance->validate($validateable);
        $this->assertTrue($result2->failed(), "returns failing result");
        $this->assertEquals(
            ["email" => ["email"], "name" => ["last" => ["required"]]],
            $result2->errors(),
            "Gets errors"
        );
    }
}
