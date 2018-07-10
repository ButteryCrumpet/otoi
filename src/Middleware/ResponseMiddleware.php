<?php

namespace Otoi\Middleware;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use SuperSimpleRequestHandler\LegacyRequestHandlerInterface;

class ResponseMiddleware
{
    private $code;
    private $headers;
    private $body;

    public function __construct($code = 200, $headers = [], $body = null)
    {
        $this->code = $code;
        $this->headers = $headers;
        $this->body = $body;
    }

    public function process(ServerRequestInterface $request, LegacyRequestHandlerInterface $handler)
    {
        return new Response($this->code, $this->headers, $this->body);
    }
}