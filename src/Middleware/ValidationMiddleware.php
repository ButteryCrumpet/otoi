<?php

namespace Otoi\Middleware;

use GuzzleHttp\Psr7\Response;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SuperSimpleValidation\RuleInterface;

class ValidationMiddleware
{
    private $validator;

    public function __construct($validator)
    {
        $this->validator = $validator;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler)
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
