<?php

namespace Otoi\Middleware;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use SuperSimpleRequestHandler\LegacyRequestHandlerInterface;

class MailMiddleware
{
    private $mailer;

    public function __construct($mailer)
    {
        $this->mailer = $mailer;
    }

    public function process(ServerRequestInterface $request, LegacyRequestHandlerInterface $handler)
    {
        if ($request->getAttribute("action", "none") === "mail") {
            if (!$this->mailer->send($request->getParsedBody(), $request->getUploadedFiles())) {
                return new Response(503);
            }
        }
        return $handler->handle($request);
    }
}
