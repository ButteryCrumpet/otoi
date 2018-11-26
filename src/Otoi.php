<?php

namespace Otoi;

use Otoi\Controllers\Admin;
use Otoi\Controllers\FormController;
use Otoi\Controllers\MailController;
use Otoi\Interfaces\FormLoaderInterface;
use Otoi\Middleware\DebugMiddleware;
use Otoi\Middleware\ErrorHandlerMiddleware;
use Otoi\Middleware\FormMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use SuperSimpleFramework\App;

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
        $this->container->register("config-dir", $configDir);
        $this->container->register("template-dir", $templateDir);
        $config = new Config();
        $this->container->register(Config::class, function () use ($config) {
            return $config;
        });
        $config["base-url"] = $this->base;
        $this->app = new App($this->container);
        $this->app->with([
            DebugMiddleware::class,
            ErrorHandlerMiddleware::class
        ]);
        $this->setRoutes();
    }

    public function run(ServerRequestInterface $request = null)
    {
        $this->app->run($request);
    }

    private function setRoutes()
    {
        $loader = $this->container->get(FormLoaderInterface::class);
        $forms = $loader->list();
        if ($key = array_search("default", $forms)) {
            $forms[$key] = "";
        }
        $regex = implode("|", $forms);
        $this->app->group($this->base, function ($group) use ($regex) {
            $group->get("/{form:$regex}[/]", FormController::class . ":index");
            $group->post( "/{form:$regex}/confirm", FormController::class . ":confirm");
            $group->post("/{form:$regex}/mail", FormController::class . ":mail");
            $group->get("/{form:$regex}/thanks", FormController::class . ":thanks");
            $group->group("/admin", function ($group) {
                $group->get("/", Admin::class . ":index");
            });
        })->with([
            "session-middleware",
            FormMiddleware::class
        ]);
    }
}
