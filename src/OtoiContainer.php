<?php

namespace Otoi;

use Otoi\Interfaces\FormLoaderInterface;
use Otoi\Interfaces\MailConfigLoaderInterface;
use Otoi\Interfaces\SessionInterface;
use Otoi\Loaders\FormLoader;
use Otoi\Loaders\MailConfigLoader;
use Otoi\Parsers\ArrayMailConfigParser;
use Otoi\Parsers\StringValidationParser;
use Otoi\Middleware\FormMiddleware;
use Otoi\Strategies\PHPFileStrategy;
use Otoi\Parsers\ArrayFieldParser;
use SuperSimpleFramework\AppContainer;
use Otoi\Interfaces\TemplateInterface;
use Otoi\Interfaces\ValidationInterface;
use Otoi\Middleware\SessionMiddleware;
use SuperSimpleValidation\Rules;

class OtoiContainer extends AppContainer
{
    public function __construct()
    {
        parent::__construct();

        $this->register("debug", true);

        $this->register(FormBox::class, function () {
            return new FormBox();
        });

        $this->register(SessionInterface::class, function($c) {
            return new BasicSession();
        });

        $this->register("rule-map", function () {
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
                'jchars' => CustomRules\JapaneseCharacters::class,
                'pdf' => CustomRules\PdfRule::class,
                'phone' => CustomRules\PhoneNumberRule::class
            ];
        });

        $this->register("validation-factory", function ($c) {
            return new StringValidationParser(
                $c->get('rule-map')
            );
        });

        $this->register(ValidationInterface::class, function ($c) {
            return new Validation(
                $c->get("validation-factory")
            );
        });

        $this->register(TemplateInterface::class, function ($c) {
            $templates = new Templates(
                $c->get("template-dir"),
                $c->get("template-cache"),
                $c->get("debug")
            );
            $templates->enablePhpIncludes();
            return $templates;
        });

        $this->register("form-file-loader", function ($c) {
            return new PHPFileStrategy($c->get("config-dir") . "/forms");
        });

        $this->register("mail-file-loader", function ($c) {
            return new PHPFileStrategy($c->get("config-dir") . "/mail");
        });

        $this->register(FormLoaderInterface::class, function ($c) {
           return new FormLoader(
               $c->get("form-file-loader"),
               $c->get("field-parser")
           );
        });

        $this->register("field-parser", function () {
            return new ArrayFieldParser();
        });

        $this->register(MailConfigLoaderInterface::class, function ($c) {
            return new MailConfigLoader(
                $c->get("mail-file-loader"),
                $c->get("mail-config-parser")
            );
        });

        $this->register("mail-config-parser", function ($c) {
            return new ArrayMailConfigParser($c->get(StringStore::class));
        });

        $this->register(StringStore::class, function () {
           return new StringStore();
        });
    }
}