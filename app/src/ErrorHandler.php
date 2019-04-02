<?php

namespace Otoi;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;
use Slim\Handlers\Error;

class ErrorHandler extends Error
{
    private $debugLevel;
    private $logger;

    public function __construct(LoggerInterface $logger, $debugLevel = 0)
    {
        $this->debugLevel = $debugLevel;
        $this->logger = $logger;
        parent::__construct(($debugLevel > 0));
    }

    public function __invoke(ServerRequestInterface $req, ResponseInterface $resp, \Exception $e)
    {
        $this->logger->critical($e->getMessage(), $this->toDetailsArray($e));
        return parent::__invoke($req, $resp, $e);
    }


    protected function renderHtmlErrorMessage(\Exception $exception)
    {
        if ($this->debugLevel > 0) {
            ob_start();
            require __DIR__ . "/_internal/error.php";
            return ob_get_clean();
        }

        $code = $exception->getCode() > 0 ? $exception->getCode() : 500;

        return "<html><head><meta http-equiv='Content-Type' content='text/html;charset=UTF-8'>".
            "<body style='margin: 5vh 0 0 10vw'><h1>{$code}</h1><p>{$exception->getMessage()}</p>".
            "</body></head></html>";
    }

    private function toDetailsArray(\Exception $e)
    {
        return [
            "code" => $e->getCode(),
            "file" => $e->getFile(),
            "line" => $e->getLine(),
            "type" => get_class($e)
        ];
    }
}