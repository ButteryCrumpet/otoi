<?php

use PHPUnit\Framework\TestCase;
use Otoi\Parsers\ArrayMailParser;

class ArrayMailParserTest extends TestCase
{
    public function testItInitializes()
    {
        $template = $this->createMock(\Otoi\Templates\TemplateInterface::class);
        $checker = $this->createMock(stdClass::class);
        $this->assertInstanceOf(
            ArrayMailParser::class,
            new ArrayMailParser($template, $checker)
        );
    }

    public function testParsesAnArray()
    {
        $array =  [
            "to" => "sleigh@hotmail.com",
            "from" => ["@name", "@email"],
            "subject" => "SUBJECT",
            "template" => "mail",
            "cc" => [["abc@123.com", "Dude"], "@him"]
        ];

        $template = $this->createMock(\Otoi\Templates\TemplateInterface::class);
        $checker = $this->createMock(stdClass::class);

        $parser = new ArrayMailParser($template, $checker);
        $output = $parser->parse($array);

        $this->assertInstanceOf(
            \Otoi\Entities\Mail::class,
            $output
        );

    }
}