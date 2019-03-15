<?php

namespace Otoi\Middleware;

use Otoi\Repositories\FormRepository;
use Otoi\Sessions\SessionInterface;
use Otoi\Validation\ValidationException;
use Otoi\Validation\ValidatorInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\NotFoundException;

class RequestValidation
{
    private $validator;
    private $repo;
    private $session;

    public function __construct(ValidatorInterface $validator, FormRepository $repo, SessionInterface $session) {
        $this->validator = $validator;
        $this->repo = $repo;
        $this->session = $session;
    }

    public function process(ServerRequestInterface $request, ResponseInterface $response, $next)
    {
        if ($request->getMethod() !== "POST") {
            return $next($request, $response);
        }

        $form = $this->getForm($request);

        if (is_null($form)) {
            throw new NotFoundException($request, $response);
        }

        $result = $this->validator->validate($form->getRules(), $request->getParsedBody());

        if ($result->failed()) {
            $this->session->flash("errors", $result->errors());
            throw (new ValidationException())
                ->setErrors($result->errors());
        }

        return $next($request->withParsedBody($result->validated()), $response);
    }


    private function getForm(ServerRequestInterface $request)
    {
        $name = $this->getFormName($request);
        if (is_null($name)) {
            return null;
        }
        return $this->repo->load($name);
    }


    private function getFormName(ServerRequestInterface $request)
    {
        $route = $request->getAttribute("route");
        if (!$route) {
            return null;
        }

        $args = $route->getArguments();

        return isset($args["form"]) ? $args["form"] : "default";
    }

}