<?php

namespace Otoi;

use Otoi\Controllers\Admin;
use Otoi\Controllers\FormController;
use Otoi\Controllers\MailController;
use Otoi\Interfaces\FormLoaderInterface;
use Otoi\Middleware\DebugMiddleware;
use Otoi\Middleware\ErrorHandlerMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use SuperSimpleFramework\App;

class Otoi
{
    private $app;
    private $base;
    private $container;

    public function __construct($base = null, $configDir = null)
    {
        $configDir = is_null($configDir) ? dirname(__FILE__) . "/config" : $configDir;
        $this->base = is_null($base) ? $_SERVER["REQUEST_URI"] : $base;
        $this->container = new OtoiContainer();
        $this->container->register("config_dir", $configDir);
        $this->app = new App($this->container);
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
        $this->app->group($this->base, function ($group) use ($forms) {
            $group->get("[/]", FormController::class . ":index");
            $group->post("confirm", FormController::class . ":confirm");
            $group->post("mail", MailController::class . ":mail");
            $group->get("thanks", FormController::class . ":thanks");
            $group->group("admin", function ($group) {
                $group->get("/", Admin::class . ":index");
            });
            foreach ($forms as $form) {
                $group->group("{" . $form . "}", function ($group) {
                    $group->get("/", FormController::class . ":display");
                    $group->post( "/confirm", FormController::class . ":confirm");
                    $group->post("/mail", FormController::class . ":mail");
                    $group->get("thanks", FormController::class . ":thanks");
                });
            }
        })->with([
            DebugMiddleware::class,
            ErrorHandlerMiddleware::class,
            "session-middleware",
        ]);
    }
}
