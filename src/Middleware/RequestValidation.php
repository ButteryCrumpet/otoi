<?php

namespace Otoi\Middleware;

use Otoi\Repositories\FormRepository;
use Otoi\Sessions\SessionInterface;
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

        $data = array_merge($request->getParsedBody(), $request->getUploadedFiles());
        $result = $this->validator->validate($form->getRules(), $data);
        if ($result->failed()) {
            $this->session->flash("errors", $result->errors());
            return $this->errorResponse($request, $response, $result->errors());
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

    private function errorResponse(ServerRequestINterface $request, ResponseInterface $response, $errors)
    {
        $params = $request->getServerParams();
        if (isset($params["HTTP_REFERER"])) {
            $splitOnQuery = explode("?", $params["HTTP_REFERER"]);
            return $response
                ->withStatus(303)
                ->withHeader("X-Otoi-Reason", "invalid")
                ->withHeader("Location", $splitOnQuery[0]);
        }

        return $response->withStatus(400);
    }

}