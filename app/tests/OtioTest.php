<?php

use PHPUnit\Framework\TestCase;
use Otoi\Otoi;

class OtoiTest extends TestCase
{
    public function testItInitializes()
    {
        $this->assertInstanceOf(
            Otoi::class,
            new Otoi("/")
        );
    }

    public function testItGetsIndex()
    {
        $instance = new Otoi("/");

        $instance->register("environment", function ($c) {
            return \Slim\Http\Environment::mock([
                "REQUEST_URI" => "/",
                'REQUEST_METHOD' => 'GET']);
        });

        $instance->register("form-repo-driver", function() {
            return $this->getDriver();
        });

        $instance->register(\Otoi\Templates\TemplateInterface::class, function() {
            $mock = $this->createMock(\Otoi\Templates\TemplateInterface::class);
            $mock->method("render")->willReturn("INDEX PAGE");
            return $mock;
        });

        $response = $instance->run(true);

        $this->assertInstanceOf(
            \Psr\Http\Message\ResponseInterface::class,
            $response
        );

        $this->assertEquals(200, $response->getStatusCode());
        $response->getBody()->rewind();
        $this->assertEquals("INDEX PAGE", $response->getBody()->getContents());

    }

    public function testItGetsConfirm()
    {

        $instance = new Otoi("/");

        $instance->register("environment", function ($c) {
            return \Slim\Http\Environment::mock([
                "REQUEST_URI" => "/confirm",
                'REQUEST_METHOD' => 'POST']);
        });

        $instance->register("form-repo-driver", function() {
            return $this->getDriver();
        });

        $instance->register(\Otoi\Security\Csrf\CsrfInterface::class, function ($c) {
            return $this->passingCsrf();
        });

        $instance->register(\Otoi\Templates\TemplateInterface::class, function() {
            $mock = $this->createMock(\Otoi\Templates\TemplateInterface::class);
            $mock->method("render")->willReturn("CONFIRMATION PAGE");
            return $mock;
        });

        $response = $instance->run(true);

        $this->assertInstanceOf(
            \Psr\Http\Message\ResponseInterface::class,
            $response
        );

        $this->assertEquals(200, $response->getStatusCode());
        $response->getBody()->rewind();
        $this->assertEquals("CONFIRMATION PAGE", $response->getBody()->getContents());
    }

    public function testItDoesValidation()
    {
        $instance = new Otoi("/");

        $instance->register("environment", function ($c) {
            return \Slim\Http\Environment::mock([
                "REQUEST_URI" => "/confirm",
                'REQUEST_METHOD' => 'POST'
            ]);
        });

        $instance->register("request", function ($c) {
            return (\Slim\Http\Request::createFromEnvironment($c->get("environment")))
                ->withParsedBody(["name" => "bob"]);
        });

        $instance->register("form-repo-driver", function() {
            return $this->getDriver(["name" => "required|whitelist:bob,terry"]);
        });

        $instance->register(\Otoi\Security\Csrf\CsrfInterface::class, function ($c) {
            return $this->passingCsrf();
        });

        $instance->register(\Otoi\Templates\TemplateInterface::class, function() {
            $mock = $this->createMock(\Otoi\Templates\TemplateInterface::class);
            $mock->method("render")->willReturn("DONE");
            return $mock;
        });

        $response = $instance->run(true);

        $this->assertInstanceOf(\Psr\Http\Message\ResponseInterface::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testItFailsValidation()
    {
        $instance = new Otoi("/");

        $instance->register("environment", function ($c) {
            return \Slim\Http\Environment::mock([
                "REQUEST_URI" => "/confirm",
                'REQUEST_METHOD' => 'POST',
            ]);
        });

        $instance->register("request", function ($c) {
            return (\Slim\Http\Request::createFromEnvironment($c->get("environment")))
                ->withParsedBody(["name" => "jones"]);
        });

        $instance->register(\Otoi\Security\Csrf\CsrfInterface::class, function ($c) {
            return $this->passingCsrf();
        });

        $instance->register("form-repo-driver", function() {
            return $this->getDriver(["name" => "required|whitelist:bob,terry"]);
        });

        $instance->register(\Otoi\Templates\TemplateInterface::class, function() {
            $mock = $this->createMock(\Otoi\Templates\TemplateInterface::class);
            $mock->method("render")->willReturn("DONE");
            return $mock;
        });

        $response = $instance->run(true);

        $this->assertInstanceOf(\Psr\Http\Message\ResponseInterface::class, $response);
        $this->assertEquals(403, $response->getStatusCode());
    }

    public function testFailsNoCsrf()
    {
        $instance = new Otoi("/");

        $instance->register("environment", function ($c) {
            return \Slim\Http\Environment::mock([
                "REQUEST_URI" => "/confirm",
                'REQUEST_METHOD' => 'POST',
            ]);
        });

        $instance->register("form-repo-driver", function() {
            return $this->getDriver();
        });

        $response = $instance->run(true);

        $this->assertInstanceOf(\Psr\Http\Message\ResponseInterface::class, $response);
        $this->assertEquals(403, $response->getStatusCode());
    }

    private function getDriver($validation = [])
    {
        $mock = $this->createMock(\Otoi\Drivers\DriverInterface::class);
        $mock->method("listing")->willReturn(["default"]);
        $mock->method("single")->willReturn([
            "validation" => $validation,
            "templates" => ["index" => "index", "confirm" => "confirm"],
            "final-location" => "/thanks"
        ]);
        return $mock;
    }

    private function passingCsrf()
    {
        $mock = $this->createMock(\Otoi\Security\Csrf\CsrfInterface::class);
        $mock->method("validateRequest")->willReturn(true);
        $mock->method("removeCsrfData")->willReturnArgument(0);
        return $mock;
    }
}