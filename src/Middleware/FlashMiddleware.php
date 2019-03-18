<?php

namespace Otoi\Middleware;


use Otoi\Sessions\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class FlashMiddleware
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function process(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        $this->session->ageFlash();
        $response = $next($request, $response);

        return $response;
    }

}