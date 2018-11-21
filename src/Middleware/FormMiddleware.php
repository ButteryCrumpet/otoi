<?php

namespace Otoi\Middleware;


use Otoi\FormBox;
use Otoi\Interfaces\FormLoaderInterface;
use Otoi\Interfaces\ValidationInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SuperSimpleFramework\Traits\RouteArgsAware;

class FormMiddleware implements MiddlewareInterface
{
    use RouteArgsAware;

    private $formBox;
    private $validator;
    private $formLoader;

    public function __construct(
        FormBox $formBox,
        ValidationInterface $validator,
        FormLoaderInterface $formLoader
    ) {
        $this->formBox = $formBox;
        $this->validator = $validator;
        $this->formLoader = $formLoader;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        $form = isset($this->routeArgs[0]) ? $this->routeArgs[0] : "default";
        $form = $this->formLoader->load($form);

        $values = $request->getParsedBody();
        foreach ($form as $field) {
            if (isset($values[$field->getName()])) {
                $field->setValue($values[$field->getName()]);
            }
            $this->validator->validate($field);
        }
        $this->formBox->set($form);
        return $handler->handle($request);
    }
}