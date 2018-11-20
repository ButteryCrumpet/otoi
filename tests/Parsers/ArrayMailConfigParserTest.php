<?php

use PHPUnit\Framework\TestCase;
use Otoi\Parsers\ArrayMailConfigParser;

class ArrayMailConfigParserTest extends TestCase
{
    public function testItInitializes()
    {
        $this->assertInstanceOf(
            ArrayMailConfigParser::class,
            new ArrayMailConfigParser()
        );
    }

    public function testParsesAnArray()
    {
        $array =  [
            "to" => "sleigh@hotmail.com",
            "from" => ["@name", "@email"],
            "subject" => "SUBJECT",
            "template" => "mail.twig.html",
            "cc" => [["abc@123.com", "Dude"], "@him"]
        ];

        $parser = new ArrayMailConfigParser();
        $output = $parser->parse($array);
        $this->assertInstanceOf(
            \Otoi\Models\EmailAddress::class,
            $output->getTo(),
            "generates Email for To"
        );
        $this->assertInstanceOf(
            \Otoi\Models\EmailAddress::class,
            $output->getFrom(),
            "generates Email for From"
        );
        $this->assertInstanceOf(
            \Otoi\StringPlaceholder::class,
            $output->getFrom()->getName(),
            "parses placeholders"
        );
        $this->assertEquals("SUBJECT", $output->getSubject());
        $this->assertEquals("mail.twig.html", $output->getTemplate());
    }
}