<?php

use Otoi\Http\Middleware\RequestValidation;
use PHPUnit\Framework\TestCase;

class RequestValidationTest extends TestCase
{
    public function test__construct()
    {
        $validator = $this->createMock(\Otoi\Validation\ValidatorInterface::class);
        $repo = $this->createMock(\Otoi\Repositories\FormRepository::class);
        $this->assertInstanceOf(
            RequestValidation::class,
            new RequestValidation($validator, $repo)
        );
    }

    public function testProcess()
    {
        $form = $this->createMock(\Otoi\Entities\Form::class);
        $form->method("getRules")
            ->willReturn([]);

        $repo = $this->createMock(\Otoi\Repositories\FormRepository::class);
        $repo->method("load")
            ->willReturn($form);

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


        $next = function ($request, $response) use ($data) {
            $this->assertInstanceOf(
                \Psr\Http\Message\ServerRequestInterface::class,
                $request
            );
            $this->assertEquals($data, $request->getParsedBody(), "parsed body only includes validated");
            return $this->createMock(\Psr\Http\Message\ResponseInterface::class);
        };

        $request = $this->createMock(\Psr\Http\Message\ServerRequestInterface::class);
        $request->method("withParsedBody")
            ->willReturnCallback(function ($data) use ($request) {
               $request->method("getParsedBody")->willReturn($data);
               return $request;
            });
        $request->method("getMethod")->willReturn("POST");
        $route = $this->createMock(\Slim\Route::class);
        $route->method("getArguments")->willReturn(["form" => "name"]);
        $request->method("getAttribute")->willReturn($route);

        $response = $this->createMock(\Psr\Http\Message\ResponseInterface::class);

        $requestToNotValidate = $this->createMock(\Psr\Http\Message\ServerRequestInterface::class);

        $instance = new RequestValidation($validator, $repo);

        $instance->process($request, $response, $next);
        $this->expectException(\Otoi\Validation\ValidationException::class);
        $instance->process($request, $response, $next);
        $instance->process($requestToNotValidate, $response, $next);
    }
}
