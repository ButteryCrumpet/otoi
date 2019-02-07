<?php

use Otoi\Middleware\RequestValidation;
use PHPUnit\Framework\TestCase;

class RequestValidationTest extends TestCase
{
    public function test__construct()
    {
        $validator = $this->createMock(\Otoi\Validation\ValidatorInterface::class);
        $this->assertInstanceOf(
            RequestValidation::class,
            new RequestValidation($validator)
        );
    }

    public function testProcess()
    {
        $result = $this->createMock(\Otoi\Validation\ValidationResultInterface::class);
        $result->method("failed")
            ->willReturn(false, true);
        $result->method("errors")
            ->willReturn([]);
        $data = ["name" => "name", "email" => "email"];
        $result->method("validated")
            ->willReturn($data);

        $validator = $this->createMock(\Otoi\Validation\ValidatorInterface::class);
        $validator->method("validate")
            ->willReturn($result);


        $handler = $this->createMock(\Psr\Http\Server\RequestHandlerInterface::class);
        $handler->method("handle")
            ->willReturnCallback(function ($request) use ($data) {
                $this->assertInstanceOf(
                    \Psr\Http\Message\ServerRequestInterface::class,
                    $request
                );
                $this->assertEquals($data, $request->getParsedBody(), "parsed body only includes validated");
                return $this->createMock(\Psr\Http\Message\ResponseInterface::class);
            });

        $request = $this->createMock([
            \Psr\Http\Message\ServerRequestInterface::class,
            \Otoi\Validation\ValidatableInterface::class
        ]);
        $request->method("withParsedBody")
            ->willReturnCallback(function ($data) use ($request) {
               $request->method("getParsedBody")->willReturn($data);
               return $request;
            });

        $requestToNotValidate = $this->createMock(\Psr\Http\Message\ServerRequestInterface::class);

        $instance = new RequestValidation($validator);

        $instance->process($request, $handler);
        $this->expectException(\Otoi\Validation\ValidationException::class);
        $instance->process($request, $handler);
        $instance->process($requestToNotValidate, $handler);
    }
}
