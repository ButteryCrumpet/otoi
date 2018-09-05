<?php

namespace Otoi\Middleware;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class MailMiddleware
{
    private $mailer;

    public function __construct($mailer)
    {
        $this->mailer = $mailer;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler)
    {

        if (!$this->mailer->send($request->getParsedBody(), $request->getUploadedFiles())) {
            return new Response(503);
        }

        $response = $handler->handle($request);
        return $response->withStatus(202);
    }
}
