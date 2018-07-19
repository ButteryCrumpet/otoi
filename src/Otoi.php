<?php

namespace Otoi;

use GuzzleHttp\Psr7\ServerRequest;
use Psr\Container\ContainerInterface;
use SuperSimpleRequestHandler\LegacyHandler as Handler;

class Otoi
{
    private $container;
    private $request;
    private $config;

    public function __construct(array $config = [], ContainerInterface $container = null)
    {
        $this->container = is_null($container)
            ? new DefaultContainer()
            : $container;

        $this->config = $this->container->get("config");
        foreach ($config as $key => $setting) {
            if (is_file($setting)) {
                $this->config->addFile($setting);
            } else {
                $this->config[$key] = $setting;
            }
        }

        $this->request = ServerRequest::fromGlobals();
    }

    public function run()
    {
        $handler = new Handler([
           $this->container->get("routing-middleware")
        ]);
        $response = $handler->handle($this->request);
        $statusCode = $response->getStatusCode();
        if ($statusCode === 404 || $statusCode === 405 || $statusCode === 503) {
            header("Location: " . $this->config["contactUri"]);
            die();
        }
        if ($statusCode  === 403 && $this->request->getMethod() === "POST") {
            header("Location: " . $this->config["contactUri"]);
            die();
        }
        if ($statusCode === 202 && $this->request->getMethod() === "POST") {
            header("Location: " . $this->config["thanksUri"]);
        }
        if ($response->hasHeader('Location')) {
            $header = 'Location: ' . $response->getHeaderLine('Location');
            header($header, true, 303);
            die();
        }
        echo $response->getBody();
    }
}
