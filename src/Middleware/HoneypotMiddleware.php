<?php

namespace Otoi\Middleware;


use Otoi\Honeypot\HoneypotInterface;
use Otoi\Honeypot\HoneypotTrapException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class HoneypotMiddleware
{
    private $honeypot;

    public function __construct(HoneypotInterface $honeypot)
    {
        $this->honeypot = $honeypot;
    }

    public function process(ServerRequestInterface $request, ResponseInterface $response, $next)
    {

        try {
            $this->honeypot->validateRequest($request, true);
        } catch (HoneypotTrapException $e) {
            return $response->withStatus(403);
        }
        return $next($request, $response);
    }
}