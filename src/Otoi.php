<?php

namespace Otoi;

use Otoi\Controllers\Admin;
use Otoi\Controllers\Form;
use Otoi\Factories\SuperSimpleValidationFactory;
use Otoi\Middleware\DebugMiddleware;
use Otoi\Middleware\ErrorHandlerMiddleware;
use Otoi\Middleware\SessionMiddleware;
use Otoi\Middleware\ValidationMiddleware;
use Psr\Http\Message\ServerRequestInterface;
use SuperSimpleValidation\Rules;
use SuperSimpleFramework\App;

class Otoi
{
    private $app;
    private $base;

    public function __construct($base = null)
    {
        $this->base = is_null($base) ? $_SERVER["REQUEST_URI"] : $base;
        $this->app = new App();
        $this->setDeps();
        $this->setRoutes();
    }

    public function run(ServerRequestInterface $request = null)
    {
        $this->app->run($request);
    }

    private function setDeps()
    {
        $this->app->register("session-middleware", function () {
            return new SessionMiddleware();
        });

        $this->app->register("rule-map", function () {
            return [
                'required' => Rules\Required::class,
                'type' => Rules\Type::class,
                'regex' => Rules\Regex::class,
                'generic' => Rules\Generic::class,
                'blacklist' => Rules\Blacklist::class,
                'whitelist' => Rules\Whitelist::class,
                'email' => Rules\Email::class,
                'file-ext' => Rules\FileExtension::class,
                'file-sig' => Rules\FileSignature::class,
                'jchars' => CustomRules\JChars::class,
                'pdf' => CustomRules\PdfRule::class,
                'phone' => CustomRules\PhoneNumberRule::class
            ];
        });

        $this->app->register(Validation::class, function ($c) {
            return new Validation(
                ["test" => "required|email"],
                $c->get("validation-factory")
            );
        });

        $this->app->register("validation-factory", function ($c) {
            return new SuperSimpleValidationFactory(
                $c->get('rule-map')
            );
        });

        $this->app->register(TemplateInterface::class, function () {
            return new Templates();
        });
    }

    private function setRoutes()
    {
        $this->app->group($this->base, function ($group) {
            $group->get("/", Form::class . ":display");
            $group->post("/confirm", Form::class . ":confirm");
            $group->get("thanks", Form::class . ":thanks");
            $group->group("/admin", function ($group) {
                $group->get("/", Admin::class . ":index");
            });
            $group->group("/{form}", function ($group) {
                $group->get("/", Form::class . ":display");
                $group->post( "/confirm", Form::class . ":confirm");
                $group->get("thanks", Form::class . ":thanks");
            });
        })->with([
            DebugMiddleware::class,
            ErrorHandlerMiddleware::class,
            "session-middleware",
            ValidationMiddleware::class
        ]);
    }
}
