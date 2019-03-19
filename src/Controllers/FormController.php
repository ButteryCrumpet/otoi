<?php

namespace Otoi\Controllers;

use Otoi\Repositories\FormRepository;
use Otoi\Sessions\SessionInterface;
use Otoi\Templates\TemplateInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Exception\NotFoundException;

class FormController
{
    private $templates;
    private $session;
    private $baseUrl;
    private $formRepo;

    public function __construct(
        TemplateInterface $templates,
        SessionInterface $session,
        FormRepository $formRepository,
        $baseUrl
    ) {
        $this->templates = $templates;
        $this->session = $session;
        $this->formRepo = $formRepository;
        $this->baseUrl = $baseUrl;
    }

    public function index(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $formName = isset($args["form"]) ? $args["form"] : "default";
        $form = $this->formRepo->load($formName);

        if (is_null($form)) {
            throw new NotFoundException($request, $response);
        }

        $body = $this->templates->render($form->getTemplateIndex(), [
            "action" => $this->buildActionUrl($formName, "confirm"),
            "data" => $request->getParsedBody(),
            "errors" => $this->session->getFlash("errors", [])
        ]);

        $response->getBody()->write($body);
        return $response;
    }

    public function confirm(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $formName = isset($args["form"]) ? $args["form"] : "default";
        $form = $this->formRepo->load($formName);

        if (is_null($form)) {
            throw new NotFoundException($request, $response);
        }

        $body = $this->templates->render($form->getTemplateConfirm(), [
            "action" => $this->buildActionUrl($formName, "mail"),
            "data" => $request->getParsedBody()
        ]);

        $response->getBody()->write($body);
        return $response;
    }

    private function buildActionUrl($formName, $action = "")
    {
        $action = $formName !== "default" ? "/$formName/$action" : "/$action";
        return ltrim($this->baseUrl, "/") . $action;
    }
}