<?php

namespace Otoi;

use Otoi\Controllers\FormController;
use Otoi\Controllers\MailController;
use Otoi\Middleware\CsrfMiddleware;
use Otoi\Middleware\RequestValidation;
use Otoi\Middleware\FlashMiddleware;
use Otoi\Middleware\SessionMiddleware;
use Otoi\Repositories\FormRepository;

class Otoi
{
    private $app;
    private $base;
    private $container;

    public function __construct($base, $configDir = null, $templateDir = null, $logDir = null)
    {
        $configDir = is_null($configDir) ? dirname(__FILE__) . "/config" : $configDir;
        $templateDir = is_null($templateDir) ? dirname(__FILE__) . '/templates' : $templateDir;
        $logDir = is_null($logDir) ? dirname(__FILE__) . "/logs" : $logDir;
        $this->base = rtrim($base, "/");

        $this->container = new OtoiContainer();
        $this->container->get('settings')['displayErrorDetails'] = true;
        $this->container["config-dir"] = $configDir;
        $this->container["template-dir"] = $templateDir;
        $this->container["log-dir"] = $logDir;

        $config = new Config();
        $this->container[Config::class] = function () use ($config) {
            return $config;
        };
        $config["base-url"] = $this->base;

        $this->app = new \Slim\App($this->container);

    }

    public function run($silent = false)
    {
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
            ->add(RequestValidation::class . ":process")
            ->add(CsrfMiddleware::class . ":process")
            ->add(FlashMiddleware::class . ":process")
            ->add(SessionMiddleware::class . ":process");
    }

}
