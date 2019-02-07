<?php

namespace Otoi\Middleware;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

// Takes an error handler and returns whatever that does after passing error
class ErrorHandlerMiddleware implements MiddlewareInterface
{
    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        try {
            return $handler->handle($request);
        } catch (\Exception $e) {
            $body = sprintf(
                $this->format(),
                get_class($e),
                $e->getMessage(),
                $e->getFile(),
                $e->getLine(),
                $this->render_file($e->getFile(), $e->getLine()),
                $this->render_trace($e)
            );
            return new Response(500, [], $body);
        }
    }

    private function render_file($filename, $lineNo)
    {
        $file = new \SplFileObject($filename);
        $line = 1;
        $str = "<code>";
        $start = $lineNo - 9;
        $stop = $lineNo + 9;
        while (!$file->eof() && $line <= $stop) {
            $line_str = $file->fgets();
            if ($line >= $start) {
                $out = $line === $lineNo ? "<span style='color:darkred'>&#10145;</span>" : "$line";
                $out .= highlight_string("<?php " . $line_str, true);
                $str .= str_replace(["&lt;?php&nbsp;", "<code>", "</code>"], "", $out);
            }
            $line = $line + 1;
        }
        $str .= "</code>";
        return $str;
    }

    private function render_trace($e) {
        $trace = $e->getTrace();
        $str = "<table style='padding:5px;font-size: 11px;background-color: lightgoldenrodyellow;'>";
        $str .= "<tr style='background-color: rgba(183,21,25,0.82);color:white;'><th>File</th><th>Function</th></tr>";
        foreach ($trace as $step) {
            $file = basename($step["file"] ?? $e->getFile());
            $class = isset($step["class"]) ? $step["class"] . "->" : "";
            $function = $step["function"];
            $line = $step["line"] ?? $e->getLine();
            $str .= "<tr>";
            $str .= "<td style='padding: 2px;border-bottom: solid 1px rgba(183,21,25,0.82);'>$file:$line</td>";
            $str .= "<td style='padding: 2px;border-bottom: solid 1px rgba(183,21,25,0.82);'>$class$function()</td>";
            $str .= "</tr>";
        }
        return $str . "</table>";
    }

    private function format() {
        $str = "<html><body style='margin:0;'>";
        $str .= "<div style='width:100vw;background-color: mediumseagreen; color:white;text-align: center;padding:5px;'>";
        $str .= "<h3 style='margin:0;'><span style='color:rebeccapurple'>%s</span> with message ";
        $str .= "<span style='color:orangered'>\"%s\"</span></h3>";
        $str .= '</div>';
        $str .= "<div style='display: flex;justify-content: space-evenly;width: 100vw;margin-top: 30px;'>";
        $str .= "<div style='padding:5px;'>";
        $str .= "<h5 style='margin:0;margin-bottom:10px;padding: 5px;text-align: center;color:white;background-color: mediumseagreen'>%s:%s</h5>";
        $str .= "%s</div>";
        $str .= "<div style='padding:5px;'>%s</div>";
        $str .= "</div>";
        $str .= "</body></html>";
        return $str;
    }
}
