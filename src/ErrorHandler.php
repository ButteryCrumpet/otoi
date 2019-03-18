<?php

namespace Otoi;

use Otoi\Csrf\InvalidCsrfException;
use Otoi\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use Slim\Handlers\Error;
use Slim\Http\Body;

class ErrorHandler
{
    protected $debugLevel;
    protected $logger;

    public function __construct(LoggerInterface $logger, $debugLevel = 0)
    {
        $this->debugLevel = $debugLevel;
        $this->logger = $logger;
    }

    public function __invoke(ServerRequestInterface $req, ResponseInterface $resp, $e)
    {
        if ($resp->getBody()->isSeekable()) {
            $resp->getBody()->rewind();
        }

        if ($e instanceof ValidationException || $e instanceof InvalidCsrfException) {

            $params = $req->getServerParams();

            $this->logger->info($e->getMessage());

            if (isset($params["HTTP_REFERER"])) {
                return $resp
                    ->withStatus(303)
                    ->withHeader("Location", $params["HTTP_REFERER"] );
            }

            $body = new Body(fopen('php://temp', 'r+'));
            $body->write($this->jsonResponse($e));

            return $resp
                ->withStatus($e->getCode())
                ->withBody($body);

        }

        $this->logger->critical($e->getMessage());
        $error = new Error(true);
        return $error($req, $resp, $e);
    }

    protected function jsonResponse(\Exception $error)
    {
        $details = [
            "code" => $error->getCode(),
            "message" => $error->getMessage()
        ];

        if ($this->debugLevel > 0) {
            $details["error"] = [
                "file" => $error->getFile(),
                "line" => $error->getLine(),
                "type" => get_class($error)
            ];
        }

        return json_encode($details);
    }

    protected function htmlResponse(\Exception $e)
    {
        return "";
    }
}