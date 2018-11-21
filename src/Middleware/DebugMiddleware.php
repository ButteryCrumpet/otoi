<?php

namespace Otoi\Middleware;

use function GuzzleHttp\Psr7\stream_for;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class DebugMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $path = $request->getUri()->getPath();
        $start = microtime(true);
        $response = $handler->handle($request);
        $ms = microtime(true) - $start;

        $body = $response->getBody()->getContents();
        $bar = $this->renderDebugBar($path, $response->getStatusCode(), $ms);

        $body = str_replace("</body>", $bar . "</body>", $body);

        return $response->withBody(stream_for($body));
    }

    private function renderDebugBar($path, $code, $time)
    {
        ob_start();
        ?>
        <div style="background-color:rgba(183,21,25,0.82);width:100%;position: fixed;bottom: 0;left: 0;">
            <div style="color:white;display: flex;justify-content: space-around">
                <p>PHP Ver. <?= phpversion() ?></p>
                <p>Path: <?= $path ?></p>
                <p>Response: <?= $code ?></p>
                <p>Time: <?= round($time, 3) ?></p>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }
}