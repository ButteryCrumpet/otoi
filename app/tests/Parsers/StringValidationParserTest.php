<?php

use Otoi\Parsers\StringValidationParser;
use PHPUnit\Framework\TestCase;
// BAD TESTS
class StringValidationParserTest extends TestCase
{

    public function test__construct()
    {
        $this->assertInstanceOf(
            StringValidationParser::class,
            new StringValidationParser([])
        );
    }

    public function testParse()
    {
        $map = [
            "test" => RuleTest::class
        ];

        $parser = new StringValidationParser($map);
        $test = $parser->parse("test:arg");
        $this->assertInstanceOf(
            \SuperSimpleValidation\Validator::class,
            $test
        );
    }
}

class RuleTest
{
    private $args;

    public function __construct($args)
    {
        $this->args = $args;
    }

    public function getArgs()
    {
        return $this->args;
    }
}
