<?php

namespace Otoi\Middleware;

use Otoi\View;
use GuzzleHttp\Psr7;
use Psr\Http\Message\ServerRequestInterface;
use SuperSimpleRequestHandler\LegacyRequestHandlerInterface;

class ViewMiddleware
{
    private $view;

    public function __construct($view)
    {
        $this->view = $view;
    }

    public function process(ServerRequestInterface $request, LegacyRequestHandlerInterface $handler)
    {
        $data = $request->getParsedBody();
        $files = $request->getUploadedFiles();
        $action = $request->getAttribute("action", "none");

        $response = $handler->handle($request);
        return $response->withBody(
            Psr7\stream_for($this->view->render($action, ["data" => $data, "files" => $files]))
        );
    }
}