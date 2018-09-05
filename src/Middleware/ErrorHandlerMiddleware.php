<?php

namespace Otoi\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ErrorHandlerMiddleware
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {
        $action = $request->getAttribute("action", "none");
        $response = $handler->handle($request);
        $code = $response->getStatusCode();

        if ($action === "input" && $code === 403) {
            return $response->withStatus(200);
        }

        if ($code === 403) {
            return $response->withHeader("Location", "/contact");
        }

//        if ($action === "mail" && $code === 200) {
//            return $response->withStatus(307)->withHeader("Location", "thanks.php");
//        }

        if ($action === "mail" && $code === 503) {
            return $response->withHeader("Location", "/contact");
        }

        return $response;
    }
}