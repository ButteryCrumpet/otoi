<?php

use Otoi\Loaders\FormLoader;
use PHPUnit\Framework\TestCase;

class FormLoaderTest extends TestCase
{

    public function testItInitialises()
    {
        $strategy = $this->createMock(\Otoi\Interfaces\StrategyInterface::class);
        $parser = $this->createMock(\Otoi\Interfaces\ParserInterface::class);

        $this->assertInstanceOf(
            FormLoader::class,
            new FormLoader($strategy, $parser)
        );
    }

    public function testLoad()
    {
        $strategy = $this->createMock(\Otoi\Interfaces\StrategyInterface::class);
        $parser = $this->createMock(\Otoi\Interfaces\ParserInterface::class);
        $strategy->expects($this->once())
            ->method("single")
            ->willReturn([["1"]]);
        $parser->expects($this->once())
            ->method("parse")
            ->willReturn($this->createMock(\Otoi\Models\Field::class));

        $loader = new FormLoader($strategy, $parser);
        $output = $loader->load("hi");
        $this->assertInstanceOf(
            \Otoi\Models\Form::class,
            $output
        );
    }

    public function testList()
    {
        $strategy = $this->createMock(\Otoi\Interfaces\StrategyInterface::class);
        $parser = $this->createMock(\Otoi\Interfaces\ParserInterface::class);
        $strategy->method("list")->willReturn(["1", "2"]);

        $loader = new FormLoader($strategy, $parser);
        $this->assertEquals(
            ["1", "2"],
            $loader->list()
        );
    }

    public function testAll()
    {
        $strategy = $this->createMock(\Otoi\Interfaces\StrategyInterface::class);
        $parser = $this->createMock(\Otoi\Interfaces\ParserInterface::class);
        $strategy->method("list")->willReturn(["1", "2"]);
        $strategy->method("single")
            ->willReturn([["1"]]);
        $parser->method("parse")
            ->willReturn($this->createMock(\Otoi\Models\Field::class));

        $loader = new FormLoader($strategy, $parser);
        $output = $loader->all();
        $this->assertInternalType("array", $output);
        foreach ($output as $form) {
            $this->assertInstanceOf(
                \Otoi\Models\Form::class,
                $form
            );
        }
    }
}
