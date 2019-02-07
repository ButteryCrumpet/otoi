<?php

namespace Otoi\Requests;

use GuzzleHttp\Psr7\LazyOpenStream;
use GuzzleHttp\Psr7\ServerRequest;
use Otoi\Validation\ValidatableInterface;
use Psr\Http\Message\ServerRequestInterface;

class FormRequest extends ServerRequest implements ValidatableInterface
{
    private $rules;

    public function __construct(
        string $method,
        $uri,
        array $headers = [],
        $body = null,
        string $version = '1.1',
        array $serverParams = [],
        $rules = []
    ) {
        parent::__construct($method, $uri, $headers, $body, $version, $serverParams);
        $this->rules = $rules;
    }

    public function data(): array
    {
        return (array)$this->getParsedBody();
    }

    public function rules(): array
    {
        return $this->rules;
    }

    public static function fromPsrRequest(ServerRequestInterface $request, array $rules)
    {
        $new = new self(
            $request->getMethod(),
            $request->getUri(),
            $request->getHeaders(),
            $request->getBody(),
            $request->getProtocolVersion(),
            $request->getServerParams(),
            $rules
        );

        return $new
            ->withCookieParams($request->getCookieParams())
            ->withQueryParams($request->getQueryParams())
            ->withParsedBody($request->getParsedBody())
            ->withUploadedFiles($request->getUploadedFiles());
    }

    public static function fromGlobals()
    {
        $method = isset($_SERVER['REQUEST_METHOD']) ? $_SERVER['REQUEST_METHOD'] : 'GET';
        $headers = function_exists('getallheaders') ? getallheaders() : [];
        $uri = self::getUriFromGlobals();
        $body = new LazyOpenStream('php://input', 'r+');
        $protocol = isset($_SERVER['SERVER_PROTOCOL']) ? str_replace('HTTP/', '', $_SERVER['SERVER_PROTOCOL']) : '1.1';

        $serverRequest = new self($method, $uri, $headers, $body, $protocol, $_SERVER);

        return $serverRequest
            ->withCookieParams($_COOKIE)
            ->withQueryParams($_GET)
            ->withParsedBody($_POST)
            ->withUploadedFiles(self::normalizeFiles($_FILES));
    }
}