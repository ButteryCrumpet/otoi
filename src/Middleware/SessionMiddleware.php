<?php

namespace Otoi\Middleware;

use Otoi\Sessions\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;


class SessionMiddleware
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function process(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        if (!$this->session->start()) {
            return $next($request, $response);
        }

        $data = $request->getParsedBody();
        $data = is_null($data) ? [] : (array)$data;
        $data = array_merge($this->session->get("otoi_data", []), $data);
        $this->session->set("otoi_data", $data);

        $response = $next($request->withParsedBody($data), $response);

        if ($this->session->condemned()) {
            $this->session->forceDestroy();
        }

        return $response;
    }
}
