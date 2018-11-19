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
        $post_data = [
            "name" => "simon",
            "email" => "abc@123.com"
        ];

        $uri = $this->createMock(\Psr\Http\Message\UriInterface::class);
        $uri->method("getPath")->willReturn("/contact/confirm");
        $request = $this->createMock(\Psr\Http\Message\ServerRequestInterface::class);
        $request->method("getUri")->willReturn($uri);
        $request->method("getMethod")->willReturn("POST");
        $request->method("getParsedBody")->willReturn($post_data);

        $otoi = new Otoi("/contact");
        $otoi->run($request);
    }
}