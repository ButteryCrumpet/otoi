<?php

namespace Otoi\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SessionMiddleware implements MiddlewareInterface
{
    private $destroy;

    public function __construct($destroy = false)
    {
        $this->destroy = $destroy;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (\headers_sent()) {
            return $handler->handle($request);
        }

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $data = (array)$request->getParsedBody() ?? [];
        if (isset($_SESSION["otoi_data"])) {
            $data = array_merge($_SESSION["otoi_data"], $data);
        }
        $_SESSION["otoi_data"] = $data;
        $response = $handler->handle($request->withParsedBody($data));

        if ($this->destroy === "success" && $response->getStatusCode() < 400) {
            session_destroy();
        }
        if ($this->destroy === "fail" && $response->getStatusCode() >= 400) {
            session_destroy();
        }
        if ($this->destroy === true) {
            session_destroy();
        }

        return $response;
    }
}
