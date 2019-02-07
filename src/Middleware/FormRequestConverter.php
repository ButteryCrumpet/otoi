<?php
/**
 * Created by PhpStorm.
 * User: apple
 * Date: 2019-02-06
 * Time: 10:55
 */

namespace Otoi\Middleware;


use Otoi\Repositories\ValidationRepositoryInterface;
use Otoi\Requests\FormRequest;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;
use SuperSimpleFramework\Interfaces\RouteArgsAwareInterface;
use SuperSimpleFramework\Traits\RouteArgsAware;

class FormRequestConverter implements MiddlewareInterface, RouteArgsAwareInterface
{
    use RouteArgsAware;

    private $repo;

    public function __construct(ValidationRepositoryInterface $repo)
    {
        $this->repo = $repo;
    }

    public function process(ServerRequestInterface $request, RequestHandlerInterface $handler): ResponseInterface
    {
        if ($request instanceof FormRequest
            || $request->getMethod() !== "POST"
            || !isset($this->routeArgs["form"])
        ) {
            return $handler->handle($request);
        }

        $rules = $this->repo->read($this->routeArgs["form"]);
        return $handler->handle(FormRequest::fromPsrRequest($request, $rules));
    }
}