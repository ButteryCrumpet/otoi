<?php

use Otoi\Middleware\FormRequestConverter;
use PHPUnit\Framework\TestCase;

class FormRequestConverterTest extends TestCase
{

    public function test__construct()
    {
        $repo = $this->createMock(\Otoi\Repositories\ValidationRepositoryInterface::class);
        $this->assertInstanceOf(
            FormRequestConverter::class,
            new FormRequestConverter($repo)
        );
    }

    public function testProcess()
    {
        $request = $this->createMock(\Psr\Http\Message\ServerRequestInterface::class);
        $request->method("getMethod")
            ->willReturn("POST");
        $request->method("getHeaders")
            ->willReturn([]);
        $request->method("getProtocolVersion")
            ->willReturn("1.1");
        $request->method("getServerParams")
            ->willReturn([]);
        $request->method("getCookieParams")
            ->willReturn([]);
        $request->method("getQueryParams")
            ->willReturn([]);
        $request->method("getParsedBody")
            ->willReturn([]);
        $request->method("getUploadedFiles")
            ->willReturn([]);

        $handler = $this->createMock(\Psr\Http\Server\RequestHandlerInterface::class);
        $handler->method("handle")
            ->willReturnCallback(function ($request) {
                $this->assertInstanceOf(
                    \Otoi\Requests\FormRequest::class,
                    $request
                );
                return $this->createMock(\Psr\Http\Message\ResponseInterface::class);
            });

        $repo = $this->createMock(\Otoi\Repositories\ValidationRepositoryInterface::class);

        $instance = new FormRequestConverter($repo);
        $instance->setRouteArgs(["form" => "formName"]);
        $instance->process($request, $handler);
    }

}
