<?php

namespace Otoi;

use Otoi\Controllers\FormController;
use Otoi\Controllers\MailController;
use Otoi\Csrf\InvalidCsrfException;
use Otoi\Middleware\CsrfMiddleware;
use Otoi\Middleware\RequestValidation;
use Otoi\Middleware\ErrorHandlerMiddleware;
use Otoi\Middleware\FlashMiddleware;
use Otoi\Middleware\SessionMiddleware;
use Otoi\Repositories\FormRepository;
use Otoi\Validation\ValidationException;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Slim\Handlers\Error;

class Otoi
{
    private $app;
    private $base;
    private $container;

    public static function quickRun($base)
    {
        $app = new Otoi($base);
        $app->run();
    }

    public function __construct($base, $configDir = null, $templateDir = null)
    {
        $configDir = is_null($configDir) ? dirname(__FILE__) . "/config" : $configDir;
        $templateDir = is_null($templateDir) ? dirname(__FILE__) . '/templates' : $templateDir;
        $this->base = rtrim($base, "/");

        $this->container = new OtoiContainer();
        $this->container["config-dir"] = $configDir;
        $this->container["template-dir"] = $templateDir;

        $config = new Config();
        $this->container[Config::class] = function () use ($config) {
            return $config;
        };
        $config["base-url"] = $this->base;

        $this->app = new \Slim\App($this->container);

    }

    public function run($silent = false)
    {
        $this->errorHandler();
        $this->setRoutes();
        return $this->app->run($silent);
    }

    public function register($name, $value)
    {
        $this->container[$name] = $value;
    }

    private function setRoutes()
    {
        $loader = $this->container->get(FormRepository::class);
        $forms = $loader->listing();
        if ($key = array_search("default", $forms)) {
            $forms[$key] = "";
        }
        $regex = implode("|", $forms);
        $this->app->group($this->base, function (\Slim\App $group) use ($regex) {
            $group->get("[/]", FormController::class . ":index");
            $group->post("/confirm", FormController::class . ":confirm");
            $group->post("/mail", MailController::class . ":mail");
            $group->get("/thanks", FormController::class . ":thanks");
            $group->get("/{form:$regex}[/]", FormController::class . ":index");
            $group->post( "/{form:$regex}/confirm", FormController::class . ":confirm");
            $group->post("/{form:$regex}/mail", MailController::class . ":mail");
            $group->get("/{form:$regex}/thanks", FormController::class . ":thanks");
        })
            ->add(SessionMiddleware::class . ":process")
            ->add(FlashMiddleware::class . ":process")
            ->add(CsrfMiddleware::class . ":process")
            //->add(ErrorHandlerMiddleware::class . ":process")
            ->add(RequestValidation::class . ":process");
    }

    private function errorHandler()
    {
        $this->container["errorHandler"] = function ($c) {
            return function (ServerRequestInterface $req, ResponseInterface $resp, $e) use ($c) {
                if ($e instanceof ValidationException || $e  instanceof InvalidCsrfException) {
                    $params = $req->getServerParams();
                    if (isset($params["HTTP_REFERER"])) {
                        return $resp->withStatus(303)
                            ->withHeader("Location", $params["HTTP_REFERER"]);
                    }
                    return $resp->withStatus(403);

                }
                $error = new Error($c->get('settings')['displayErrorDetails']);
                return $error($req, $resp, $e);
            };
        };
    }

}
