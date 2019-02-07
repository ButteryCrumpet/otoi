<?php

namespace Otoi\Middleware;

use Otoi\Interfaces\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class SessionMiddleware implements MiddlewareInterface
{
    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if (!$this->session->start()) {
            return $handler->handle($request);
        }

        $data = (array)$request->getParsedBody() ?? [];
        $data = array_merge($this->session->get("otoi_data", []), $data);
        $this->session->set("otoi_data", $data);

        $response = $handler->handle($request->withParsedBody($data));

        if ($this->session->isCondemned()) {
            $this->session->forceDestroy();
        }

        return $response;
    }
}
