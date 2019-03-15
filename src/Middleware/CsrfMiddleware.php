<?php

namespace Otoi\Middleware;

use Otoi\Csrf\CsrfInterface;
use Otoi\Csrf\InvalidCsrfException;
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

        if (!$this->csrf->validateRequest($request)) {
            throw new InvalidCsrfException();
        }

        return $next($this->csrf->removeCsrfData($request), $response);
    }
}