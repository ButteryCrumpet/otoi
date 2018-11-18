<?php

namespace Otoi\Middleware;

use GuzzleHttp\Psr7\Response;
use Otoi\Validation;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class ValidationMiddleware implements MiddlewareInterface
{
    private $validator;

    public function __construct(Validation $validator)
    {
        $this->validator = $validator;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $valid = $this->validator->validate(array_merge(
            (array)$request->getParsedBody() ?? [],
            $request->getUploadedFiles() ?? []
        ));
        if (!$valid) {
            return new Response(403);
        }
        return $handler->handle($request);
    }
}
