<?php

namespace Otoi\Middleware;

use \Psr\Http\Message\ServerRequestInterface;
use \GuzzleHttp\Psr7\Response;
use SuperSimpleRequestHandler\LegacyRequestHandlerInterface;
use SuperSimpleRequestHandler\LegacyHandler;

class RoutingMiddleware
{
    private $dispatcher;
    private $routes = [];
    private $request;

    public function addRoute($name, $method, $url, $middleware)
    {
        $this->routes[$name] = [
            "method" => $method,
            "url" => $url,
            "middleware" => $middleware
        ];
    }

    public function process(ServerRequestInterface $request, LegacyRequestHandlerInterface $handler)
    {
        $this->request = $request;
        $this->setUpDispatcher();
        return $this->dispatch();
    }

    private function setUpDispatcher()
    {
        $this->dispatcher = \FastRoute\simpleDispatcher(function (\FastRoute\RouteCollector $r) {
            foreach ($this->routes as $name => $route) {
                $r->addRoute($route["method"], $route["url"], function () use ($name, $route) {
                    $handler = new LegacyHandler($route["middleware"]);
                    return $handler->handle($this->request->withAttribute("action", $name));
                });
            }
        });
    }

    private function dispatch()
    {
        $httpMethod = $this->request->getMethod();
        $uri = $this->request->getUri()->getPath();
        $routeInfo = $this->dispatcher->dispatch($httpMethod, $uri);

        switch ($routeInfo[0]) {
            case \FastRoute\Dispatcher::NOT_FOUND:
                return new Response(404);
                break;
            case \FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
                return new Response(405);
                break;
            case \FastRoute\Dispatcher::FOUND:
                $handler = $routeInfo[1];
                return $handler();
                break;
        }
    }
}
