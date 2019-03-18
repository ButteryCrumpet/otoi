<?php

namespace Otoi;

use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Otoi\Controllers\FormController;
use Otoi\Controllers\MailController;
use Otoi\Csrf\CsrfInterface;
use Otoi\Csrf\CsrfRenderer;
use Otoi\Csrf\SessionCsrf;
use Otoi\Mail\DriverInterface as MailDriver;
use Otoi\Mail\Drivers\PHPMailerDriver;
use Otoi\Middleware\CsrfMiddleware;
use Otoi\Middleware\FlashMiddleware;
use Otoi\Middleware\SessionMiddleware;
use Otoi\Sessions\BasicSession;
use Otoi\Sessions\SessionInterface;
use Otoi\Repositories\FormRepository;
use Otoi\Repositories\MailRepository;
use Otoi\Parsers\ArrayMailParser;
use Otoi\Parsers\StringValidationParser;
use Otoi\Middleware\RequestValidation;
use Otoi\Drivers\PHPFileDriver;
use Otoi\Templates\PHPTemplates;
use Otoi\Templates\TemplateInterface;
use Otoi\Validation\Validator;
use Otoi\Validation\ValidatorInterface;
use Psr\Log\LoggerInterface;
use Slim\Container;
use SuperSimpleValidation\Rules;

class OtoiContainer extends Container
{
    public function __construct()
    {
        parent::__construct();

        $this["debug"] = true;

        // Controllers --

        $this[FormController::class] = function ($c) {
            return new FormController(
                $c->get(TemplateInterface::class),
                $c->get(SessionInterface::class),
                $c->get(FormRepository::class),
                $c->get(Config::class)
            );
        };

        $this[MailController::class] = function ($c) {
            return new MailController(
                $c->get(MailDriver::class),
                $c->get(FormRepository::class),
                $c->get(MailRepository::class),
                $c->get(SessionInterface::class)
            );
        };

        // --Controllers

        // Middleware --

        $this[SessionMiddleware::class] = function ($c) {
            return new SessionMiddleware($this->get(SessionInterface::class));
        };

        $this[FlashMiddleware::class] = function ($c) {
            return new FlashMiddleware($this->get(SessionInterface::class));
        };

        $this[RequestValidation::class] = function ($c) {
            return new RequestValidation(
                $c->get(ValidatorInterface::class),
                $c->get(FormRepository::class),
                $c->get(SessionInterface::class)
            );
        };

        // -- Middleware

        // Validation --

        $this["rules"] = function () {
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
                'jchars' => Validation\CustomRules\JapaneseCharacters::class,
                'pdf' => Validation\CustomRules\PdfRule::class,
                'phone' => Validation\CustomRules\PhoneNumberRule::class
            ];
        };

        $this["validation-factory"] = function ($c) {
            return new StringValidationParser(
                $c->get('rules')
            );
        };

        $this[ValidatorInterface::class] = function ($c) {
            return new Validator(
                $c->get("validation-factory")
            );
        };

        // -- Validation

        // Templating --

        $this[TemplateInterface::class] = function ($c) {
            $templates = new PHPTemplates(
                $c->get("template-dir"),
                ["csrf" => new CsrfRenderer($c->get(CsrfInterface::class))]
            );
            return $templates;
        };

        // -- Templating

        // Repositories --

        $this[FormRepository::class] = function ($c) {
           return new FormRepository(
               $c->get("form-repo-driver")
           );
        };

        $this[MailRepository::class] = function ($c) {
            return new MailRepository(
                $c->get("mail-repo-driver"),
                $c->get("mail-config-parser")
            );
        };

        $this["form-repo-driver"] = function ($c) {
            return new PHPFileDriver($c->get("config-dir") . "/forms");
        };

        $this["mail-repo-driver"] = function ($c) {
            return new PHPFileDriver($c->get("config-dir") . "/mail");
        };

        $this["mail-config-parser"] = function ($c) {
            return new ArrayMailParser(
                $c->get(TemplateInterface::class),
                new ConditionCheck() // Do with validation?
            );
        };

        $this[MailDriver::class] = function ($c) {
            return new PHPMailerDriver();
        };

        // -- Repositories

        $this["errorHandler"] = function ($c) {
            return new ErrorHandler($c->get(LoggerInterface::class), 1);
        };

        $this[LoggerInterface::class] = function ($c) {
            return new Logger("file", [
                new StreamHandler($c->get("log-dir") . "/all.log")
            ]);
        };

        $this[CsrfMiddleware::class] = function ($c) {
            return new CsrfMiddleware($c->get(CsrfInterface::class));
        };

        $this[CsrfInterface::class] = function ($c) {
            return new SessionCsrf("otoi", $c->get(SessionInterface::class));
        };

        $this[SessionInterface::class] = function($c) {
            return new BasicSession();
        };
    }
}