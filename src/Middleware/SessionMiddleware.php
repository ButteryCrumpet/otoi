<?php

namespace Otoi\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use SuperSimpleRequestHandler\LegacyRequestHandlerInterface;

class SessionMiddleware
{
    public function process(ServerRequestInterface $request, LegacyRequestHandlerInterface $handler)
    {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $data = $request->getParsedBody();
        if (isset($_SESSION["otoi_data"])) {
            $data = array_merge($_SESSION["otoi_data"], $data);
        }
        $_SESSION["otoi_data"] = $data;
        return $handler->handle($request->withParsedBody($data));
    }
}
