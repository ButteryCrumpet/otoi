<?php

namespace Otoi\Middleware;

use Psr\Http\Message\ServerRequestInterface;
use SuperSimpleRequestHandler\LegacyRequestHandlerInterface;

class SessionMiddleware
{
    public function process(ServerRequestInterface $request, LegacyRequestHandlerInterface $handler)
    {

        $action = $request->getAttribute("action", "none");

        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $data = $request->getParsedBody();
        if (isset($_SESSION["otoi_data"])) {
            $data = array_merge($_SESSION["otoi_data"], $data);
        }
        $_SESSION["otoi_data"] = $data;
        $response = $handler->handle($request->withParsedBody($data));

        if ($action === "mail" && $response->getStatusCode() === 200) {
            session_destroy();
        }
        return $response;
    }
}
