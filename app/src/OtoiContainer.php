<?php

namespace Otoi;

use function Otoi\Functions\getenv_or;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Otoi\Http\Controllers\FormController;
use Otoi\Http\Controllers\MailController;
use Otoi\Security\Csrf\CsrfInterface;
use Otoi\Security\Csrf\CsrfRenderer;
use Otoi\Security\Csrf\SessionCsrf;
use Otoi\Security\Honeypot\Honeypot;
use Otoi\Security\Honeypot\HoneypotInterface;
use Otoi\Mail\DriverInterface as MailDriver;
use Otoi\Mail\DriverInterface;
use Otoi\Mail\Drivers\PHPMailerDriver;
use Otoi\Http\Middleware\CsrfMiddleware;
use Otoi\Http\Middleware\FileSessionMiddleware;
use Otoi\Http\Middleware\FlashMiddleware;
use Otoi\Http\Middleware\HoneypotMiddleware;
use Otoi\Http\Middleware\RequestLogMiddleware;
use Otoi\Http\Middleware\SessionMiddleware;
use Otoi\Http\Sessions\BasicSession;
use Otoi\Http\Sessions\SessionInterface;
use Otoi\Repositories\FormRepository;
use Otoi\Repositories\MailRepository;
use Otoi\Parsers\ArrayMailParser;
use Otoi\Parsers\StringValidationParser;
use Otoi\Http\Middleware\RequestValidation;
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

        // SETTINGS --

        $this["config"] = [
            "debug" => strtolower(getenv_or("DEBUG", "FALSE")) === "true",
            "base-url" => getenv_or("BASE_URL", "/"),
            "storage" => realpath(getenv_or("STORAGE", __DIR__."/storage")),
            "config-dir" => realpath(getenv_or("CONFIG", "./config")),
            "template-dir" => realpath(getenv_or("TEMPLATES", "./templates")),
            "log-dir" => realpath(getenv_or("LOGS", "./logs")),
        ];


        // -- SETTINGS

        // Controllers --

        $this[FormController::class] = function ($c) {
            return new FormController(
                $c->get(TemplateInterface::class),
                $c->get(SessionInterface::class),
                $c->get(FormRepository::class),
                $c->get("config")["base-url"]
            );
        };

        $this[MailController::class] = function ($c) {
            return new MailController(
                $c->get(MailDriver::class),
                $c->get(FormRepository::class),
                $c->get(MailRepository::class),
                $c->get(SessionInterface::class),
                $c->get(LoggerInterface::class)
            );
        };

        // --Controllers

        // Middleware --

        $this[SessionMiddleware::class] = function ($c) {
            return new SessionMiddleware($c->get(SessionInterface::class));
        };

        $this[FileSessionMiddleware::class] = function ($c) {
            return new FileSessionMiddleware($c->get("config")["storage"], $c->get(SessionInterface::class));
        };

        $this[FlashMiddleware::class] = function ($c) {
            return new FlashMiddleware($c->get(SessionInterface::class));
        };

        $this[RequestValidation::class] = function ($c) {
            return new RequestValidation(
                $c->get(ValidatorInterface::class),
                $c->get(FormRepository::class),
                $c->get(SessionInterface::class)
            );
        };

        $this[CsrfMiddleware::class] = function ($c) {
            return new CsrfMiddleware($c->get(CsrfInterface::class));
        };

        $this[HoneypotMiddleware::class] = function ($c) {
            return new HoneypotMiddleware($c->get(HoneypotInterface::class));
        };

        $this[RequestLogMiddleware::class] = function ($c) {
            $file = $c->get("config")["log-dir"] . "/access.log";
            return new RequestLogMiddleware(new Logger(
                "access",
                [new StreamHandler($file)]
            ));
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
                $c->get("config")["template-dir"],
                [
                    "csrf" => new CsrfRenderer($c->get(CsrfInterface::class)),
                    "honeypot" => $c->get(HoneypotInterface::class)
                ]
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
            return new PHPFileDriver($c->get("config")["config-dir"] . "/forms.php");
        };

        $this["mail-repo-driver"] = function ($c) {
            return new PHPFileDriver($c->get("config")["config-dir"] . "/mail.php");
        };

        $this["mail-config-parser"] = function ($c) {
            return new ArrayMailParser(
                $c->get(DriverInterface::class),
                $c->get(TemplateInterface::class),
                new ConditionCheck() // Do with validation?
            );
        };

        $this[MailDriver::class] = function ($c) {
            return new PHPMailerDriver([
                "driver" => getenv_or("MAIL_DRIVER", "mail"),
                "lang" => getenv_or("MAIL_LANG", "jp"),
                "host" => getenv_or("SMTP_HOST", ""),
                "smtpAuth" => !empty(getenv_or("SMTP_USER")),
                "username" => getenv_or("SMTP_USER", ""),
                "password" => getenv_or("SMTP_PASS", ""),
                "port" => getenv_or("PORT", 25),
            ]);
        };

        // -- Repositories

        $this["errorHandler"] = function ($c) {
            $debug = $c->get("config")["debug"] ? 1 : 0;
            return new ErrorHandler($c->get(LoggerInterface::class), $debug);
        };

        // Logging --

        $this[LoggerInterface::class] = function ($c) {
            $logger = new Logger("file");
            $logger->pushHandler(new StreamHandler($c->get("config")["log-dir"] . "/info.log"));
            $logger->pushHandler(new StreamHandler($c->get("config")["log-dir"] . "/errors.log", Logger::ERROR, false));
            return $logger;
        };

        // -- Logging

        $this[CsrfInterface::class] = function ($c) {
            return new SessionCsrf("otoi", $c->get(SessionInterface::class));
        };

        $this[SessionInterface::class] = function ($c) {
            return new BasicSession();
        };

        $this[HoneypotInterface::class] = function ($c) {
            return new Honeypot("fax_confirm_");
        };
    }
}