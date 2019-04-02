<?php

use PHPUnit\Framework\TestCase;
use Otoi\Parsers\ArrayFieldParser;

class ArrayFieldParserTest extends TestCase
{
    public function testItInitializes()
    {
        $this->assertInstanceOf(
            ArrayFieldParser::class,
            new ArrayFieldParser()
        );
    }

    public function testItParsesArray()
    {
        $parser = new ArrayFieldParser();
        $field = [
            "name" => "field-name",
            "type" => "text",
            "defaultValue" => "Your name",
            "validation" => "required"
        ];
        $output = $parser->parse($field);
        $this->assertEquals(
            "field-name",
            $output->getName()
        );
        $this->assertEquals(
            "text",
            $output->getType()
        );
        $this->assertEquals(
            "required",
            $output->getValidation()
        );
        $this->assertEquals(
            "Your name",
            $output->getDefaultValue()
        );
    }

    public function testThrowsExceptionWhenNoNameValue()
    {
        $this->expectException(\DomainException::class);
        $parser = new ArrayFieldParser();
        $field = ["type" => "text"];
        $parser->parse($field);
    }
}