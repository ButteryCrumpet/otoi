<?php

namespace Otoi\Middleware;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ErrorHandlerMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            $response = $handler->handle($request);
        } catch (\Exception $e) {
            $body = sprintf(
                "<html><body><h5>%s with message \"%s\"</h5><h5>File: %s:%s</h5>%s</body></html>",
                get_class($e),
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
                $this->render($e->getFile(), $e->getLine())
                //highlight_file($e->getFile(), true)
            );
            return new Response(500, [], $body);
        }

        return $response;
    }

    public function render($filename, $lineNo)
    {
        $file = new \SplFileObject($filename);
        $line = 1;
        $str = "<code>";
        $start = $lineNo - 5;
        $stop = $lineNo + 5;
        while (!$file->eof() && $line <= $stop) {
            $line_str = $file->fgets();
            if ($line >= $start) {
                $out = $line === $lineNo ? "<span style='color:darkred'>$line</span>" : "$line";
                $out .= highlight_string("<?php " . $line_str . " ?>", true);
                $str .= str_replace(["&lt;?php&nbsp;", "?&gt;", "<code>", "</code>"], "", $out);
            }
            $line = $line + 1;
        }
        $str .= "</code>";
        return $str;
    }
}
