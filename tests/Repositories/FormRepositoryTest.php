<?php

use Otoi\Repositories\FormRepository;
use PHPUnit\Framework\TestCase;

class FormRepositoryTest extends TestCase
{

    private $schema = [
        "templates" => [
            "index" => "file1",
            "confirm" => "file2",
            "final" => "file3"
        ],
        "validation" => [
            "name" => "required",
            "selection.*" => "whitelist:1,2,3,4,5"
        ]
    ];

    public function testItInitialises()
    {
        $driver = $this->createMock(\Otoi\Drivers\DriverInterface::class);

        $this->assertInstanceOf(
            FormRepository::class,
            new FormRepository($driver)
        );
    }

    public function testLoad()
    {
        $driver = $this->createMock(\Otoi\Drivers\DriverInterface::class);
        $driver->expects($this->once())
            ->method("single")
            ->willReturn($this->schema);

        $loader = new FormRepository($driver);
        $output = $loader->load("hi");
        $this->assertInstanceOf(
            \Otoi\Entities\Form::class,
            $output
        );
    }

    public function testNames()
    {
        $driver = $this->createMock(\Otoi\Drivers\DriverInterface::class);
        $driver->method("listing")->willReturn(["1", "2"]);

        $loader = new FormRepository($driver);
        $this->assertEquals(
            ["1", "2"],
            $loader->listing()
        );
    }

    public function testAll()
    {
        $driver = $this->createMock(\Otoi\Drivers\DriverInterface::class);
        $driver->method("listing")->willReturn(["1", "2"]);
        $driver->method("single")
            ->willReturn($this->schema);

        $loader = new FormRepository($driver);
        $output = $loader->all();
        $this->assertInternalType("array", $output);
        foreach ($output as $form) {
            $this->assertInstanceOf(
                \Otoi\Entities\Form::class,
                $form
            );
        }
    }
}
