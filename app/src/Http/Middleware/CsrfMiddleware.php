<?php

namespace Otoi\Http\Middleware;

use Otoi\Security\Csrf\CsrfInterface;
use Otoi\Security\Csrf\InvalidCsrfException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class CsrfMiddleware
{
    private $csrf;

    public function __construct(CsrfInterface $csrf)
    {
        $this->csrf = $csrf;
    }

    public function process(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        if (!in_array($request->getMethod(), ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            return $next($request, $response);
        }

        try {
            $this->csrf->validateRequest($request);
        } catch (InvalidCsrfException $e) {
            return $this->errorResponse($request, $response, $e);
        }

        return $next($this->csrf->removeCsrfData($request), $response);
    }

    private function errorResponse(ServerRequestINterface $request, ResponseInterface $response, InvalidCsrfException $e)
    {
        $params = $request->getServerParams();
        if (isset($params["HTTP_REFERER"])) {
            $splitOnQuery = explode("?", $params["HTTP_REFERER"]);
            return $response
                ->withStatus(303)
                ->withHeader("Location", $splitOnQuery[0]);
        }

        return $response->withStatus($e->getCode());
    }
}