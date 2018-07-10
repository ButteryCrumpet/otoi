<?php

namespace Otoi;

use GuzzleHttp\Psr7\ServerRequest;
use Psr\Container\ContainerInterface;
use SuperSimpleRequestHandler\LegacyHandler as Handler;

class Otoi
{
    private $container;
    private $handler;
    private $request;

    public function __construct(ContainerInterface $container = null)
    {
        $this->container = is_null($container)
            ? new DefaultContainer()
            : $container;

        $this->handler = new Handler([
            $this->container->get("error-handler-middleware"),
            $this->container->get("file-session-middleware"),
            $this->container->get("session-middleware"),
            $this->container->get("view-middleware"),
            $this->container->get("validation-middleware"),
            $this->container->get("mail-middleware"),
            $this->container->get("default-response-middleware")
        ]);
        $this->request = ServerRequest::fromGlobals();
    }

    public function input()
    {
        $request = $this->request->withAttribute("action", "input");
        return $this->handler->handle($request);
    }

    public function confirm()
    {
        $request = $this->request->withAttribute("action", "confirm");
        return $this->handler->handle($request);
    }

    public function mail()
    {
        $request = $this->request->withAttribute("action", "mail");
        return $this->handler->handle($request);
    }
}
