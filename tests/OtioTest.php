<?php

use PHPUnit\Framework\TestCase;
use Otoi\Otoi;

class OtoiTest extends TestCase
{
    public function testItInitializes()
    {
        $this->assertInstanceOf(
            Otoi::class,
            new Otoi()
        );
    }

    public function testItRuns()
    {
        $uri = $this->createMock(\Psr\Http\Message\UriInterface::class);
        $uri->method("getPath")->willReturn("/contact/confirm");
        $request = $this->createMock(\Psr\Http\Message\ServerRequestInterface::class);
        $request->method("getUri")->willReturn($uri);
        $request->method("getMethod")->willReturn("POST");
        $request->method("getParsedBody")->willReturn(["test" => "abc@def.com"]);

        $otoi = new Otoi("/contact");
        $otoi->run($request);
    }
}