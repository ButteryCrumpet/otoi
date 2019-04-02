<?php

namespace Otoi\Http\Middleware;


use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class RequestLogMiddleware
{
    private $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    public function process(ServerRequestInterface $request, ResponseInterface $response, $next)
    {

        $id = uniqid();
        $response = $next($request, $response);
        $this->logger->info($this->formatRequest($request, $response));
        return $response;

    }

    private function formatRequest(ServerRequestInterface $request, ResponseInterface $response)
    {

        $params = $request->getServerParams();

        $format = "%s %s %s %s %s %s";
        $method = $request->getMethod();
        $rCode = $response->getStatusCode();
        $url = $request->getUri()->getPath();
        $referer =  isset($params["HTTP_REFERER"]) ? $params["HTTP_REFERER"] : "-";
        $ip = isset($params["REMOTE_ADDR"]) ? $params["REMOTE_ADDR"] : "-";
        $agent = implode(" ", $request->getHeader("User-Agent"));

        return sprintf($format, $method, $rCode, $url, $referer, $ip, $agent);
    }
}