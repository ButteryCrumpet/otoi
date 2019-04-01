<?php

namespace Otoi;

use Otoi\Controllers\FormController;
use Otoi\Controllers\MailController;
use Otoi\Middleware\CsrfMiddleware;
use Otoi\Middleware\FileSessionMiddleware;
use Otoi\Middleware\HoneypotMiddleware;
use Otoi\Middleware\RequestLogMiddleware;
use Otoi\Middleware\RequestValidation;
use Otoi\Middleware\FlashMiddleware;
use Otoi\Middleware\SessionMiddleware;
use Slim\App;

class Otoi
{
    private $app;
    private $container;

    public function __construct(array $config = [])
    {
        $this->container = new OtoiContainer();
        $this->container["config"] = array_merge($this->container["config"], $config);
        if ($this->container["config"]["debug"]) {
            $this->container->get("settings")["displayErrorDetails"] = true;
        }
        $this->app = new App($this->container);
    }

    public function run($silent = false)
    {
        try {
            $this->setUp();
        } catch (\Exception $e) {
            echo sprintf(_('Configuration error occurred: ')) . get_class($e) . " - " . $e->getMessage();
            die();
        }

        return $this->app->run($silent);
    }

    public function register($name, $value)
    {
        $this->container[$name] = $value;
    }

    private function setUp()
    {

        $base = ltrim($this->container->get("config")["base-url"], "/");

        $this->app->group($base . "/", function (App $group) {

            $group->get("[/]", FormController::class . ":index");
            $group->post("confirm", FormController::class . ":confirm");
            $group->post("mail", MailController::class . ":mail");

            $group->get("{form}[/]", FormController::class . ":index");
            $group->post( "{form}/confirm", FormController::class . ":confirm");
            $group->post("{form}/mail", MailController::class . ":mail");

        })
            ->add(RequestValidation::class . ":process")
            ->add(FileSessionMiddleware::class . ":process")
            ->add(CsrfMiddleware::class . ":process")
            ->add(FlashMiddleware::class . ":process")
            ->add(SessionMiddleware::class . ":process")
            ->add(HoneypotMiddleware::class . ":process")
            ->add(RequestLogMiddleware::class . ":process");
    }

}
