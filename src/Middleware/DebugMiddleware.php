<?php

namespace Otoi\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DebugMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        var_dump($request->getUri()->getPath());
        $start = microtime(true);
        $response = $handler->handle($request);
        $ms = microtime(true) - $start;
        echo sprintf("\n%s Response in %s seconds.\n", $response->getStatusCode(), $ms);
        return $response;
    }
}