<?php

namespace Otoi;

use Dotenv\Dotenv;
use Otoi\Http\Controllers\FormController;
use Otoi\Http\Controllers\MailController;
use Otoi\Http\Middleware\CsrfMiddleware;
use Otoi\Http\Middleware\FileSessionMiddleware;
use Otoi\Http\Middleware\HoneypotMiddleware;
use Otoi\Http\Middleware\RequestLogMiddleware;
use Otoi\Http\Middleware\RequestValidation;
use Otoi\Http\Middleware\FlashMiddleware;
use Otoi\Http\Middleware\SessionMiddleware;
use Slim\App;

class Otoi
{
    private $app;
    private $container;

    static public function start($config = null, $silent = false)
    {
        $app = new static($config);
        $app->run($silent);
        return $app;
    }

    public function __construct($config = null)
    {
        $legit_file = is_string($config) && is_dir($config);
        $config_file = $legit_file ? $config : $this->findConfigFile();
        if ($config_file) {
            $env = Dotenv::create($config_file);
            $env->load();
        }

        $this->container = new OtoiContainer();
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

        $this->app->group($base, function (App $group) {

            $group->get("[/]", FormController::class . ":index");
            $group->post("/confirm", FormController::class . ":confirm");
            $group->post("/mail", MailController::class . ":mail");

            $group->get("/{form}[/]", FormController::class . ":index");
            $group->post( "/{form}/confirm", FormController::class . ":confirm");
            $group->post("/{form}/mail", MailController::class . ":mail");

        })
            ->add(RequestValidation::class . ":process")
            ->add(FileSessionMiddleware::class . ":process")
            ->add(CsrfMiddleware::class . ":process")
            ->add(FlashMiddleware::class . ":process")
            ->add(SessionMiddleware::class . ":process")
            ->add(RequestLogMiddleware::class . ":process");

        if ($this->container["config"]["honeypot"]) {
            $this->app->add(HoneypotMiddleware::class . ":process");
        }
    }

    private function findConfigFile()
    {
        if (file_exists(".env")) {
            return dirname(realpath(".env"));
        }

        if (file_exists("config/.env")) {
            return dirname(realpath("config/.env"));
        }

        if (file_exists(__DIR__."/../.env")) {
            return dirname(realpath(__DIR__."/.env"));
        }

        if (file_exists(__DIR__."/.env")) {
            return dirname(realpath(__DIR__."/../.env"));
        }

        return false;
    }

}
