<?php

namespace Otoi;

use Otoi\CustomRules;
use Otoi\Factories\SuperSimpleValidationFactory;
use Otoi\Middleware\ErrorHandlerMiddleware;
use Otoi\Middleware\FileSessionMiddleware;
use Otoi\Middleware\MailMiddleware;
use Otoi\Middleware\ResponseMiddleware;
use Otoi\Middleware\SessionMiddleware;
use Otoi\Middleware\ValidationMiddleware;
use Otoi\Middleware\ViewMiddleware;
use SuperSimpleCache\SuperSimpleCache;
use SuperSimpleTemplates\TemplateFactory;
use SuperSimpleValidation\Rules;
use SuperSimpleDI\Container;

class DefaultContainer extends Container
{
    public function __construct()
    {
        $this->register("config", function ($c) {
            return new Config("src/config-test.php");
        });

        $this->initViews();
        $this->initMail();
        $this->initSessions();
        $this->initValidation();
        $this->initResponseHandling();
    }

    /*
     * View Dependencies
     **/
    private function initViews()
    {
        $this->register("view-middleware", function ($c) {
            return new ViewMiddleware(
                $c->get("views")
            );
        });

        $this->register("views", function ($c) {
           return new View(
               $c->get("config")["view-actions"],
               $c->get("template-engine")
           );
        });

        $this->register("template-engine", function ($c) {
            $helpers = $c->has("template-helpers")
                ? $c->get("template-helpers")
                : [];
            return new TemplateFactory(
                $c->get("config")["template-dir"],
                $helpers
            );
        });

        $this->register("template-helpers", function ($c) {
            return [
                "validation" => $c->get("validation")
            ];
        });
    }

    /*
     * Session Dependencies
     * */
    private function initSessions()
    {
        $this->register("session-middleware", function ($c) {
            return new SessionMiddleware();
        });

        $this->register("file-session-middleware", function ($c) {
            return new FileSessionMiddleware(
                $c->get("cache")
            );
        });

        $this->register("cache", function ($c) {
            return new SuperSimpleCache(
                $c->get("config")["cache-dir"]
            );
        });
    }

    /*
     * Mail Dependencies
     * */
    private function initMail()
    {
        $this->register("mail-middleware", function ($c) {
            return new MailMiddleware(
                $c->get("mailer")
            );
        });

        $this->register("mailer", function ($c) {
            return new Mailer(
                $c->get("config")["mail"],
                $c->get('views')
            );
        });
    }

    /*
     * Validation Dependencies
     * */
    private function initValidation()
    {
        $this->register("rule-map", function ($c) {
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

        $this->register("validation-middleware", function ($c) {
            return new ValidationMiddleware(
                $c->get("validation")
            );
        });

        $this->register("validation", function ($c) {
            return new Validation(
                $c->get("config")["validation"],
                $c->get("validation-factory")
            );
        });

        $this->register("validation-factory", function ($c) {
            return new SuperSimpleValidationFactory(
                $c->get('rule-map')
            );
        });
    }

    /*
     * Response handling Dependencies
     * */
    private function initResponseHandling()
    {
        $this->register("default-response-middleware", function ($c) {
            return new ResponseMiddleware();
        });

        $this->register("error-handler-middleware", function ($c) {
            return new ErrorHandlerMiddleware(
                $c->get("config")["error-conf"]
            );
        });
    }

}