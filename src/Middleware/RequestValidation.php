<?php

namespace Otoi\Middleware;

use Otoi\Validation\ValidationException;
use Otoi\Validation\ValidatorInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use Otoi\Validation\ValidatableInterface;

class RequestValidation implements MiddlewareInterface
{
    private $validator;

    public function __construct(ValidatorInterface $validator) {
        $this->validator = $validator;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request instanceof ValidatableInterface) {
            $result = $this->validator->validate($request);
            if ($result->failed()) {
                throw (new ValidationException())
                    ->setErrors($result->errors());
            }
            $request = $request->withParsedBody($result->validated());
        }

        return $handler->handle($request);
    }
}