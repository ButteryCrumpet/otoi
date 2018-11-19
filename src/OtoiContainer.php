<?php

namespace Otoi;

use SuperSimpleFramework\AppContainer;
use Otoi\Factories\SuperSimpleValidationFactory;
use Otoi\Interfaces\LoaderInterface;
use Otoi\Interfaces\TemplateInterface;
use Otoi\Interfaces\ValidationInterface;
use Otoi\Loaders\PHPFileLoader;
use Otoi\Middleware\SessionMiddleware;
use SuperSimpleValidation\Rules;

class OtoiContainer extends AppContainer
{
    public function __construct()
    {
        parent::__construct();
        
        $this->register("session-middleware", function () {
            return new SessionMiddleware();
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
                'jchars' => CustomRules\JChars::class,
                'pdf' => CustomRules\PdfRule::class,
                'phone' => CustomRules\PhoneNumberRule::class
            ];
        });

        $this->register("validation-factory", function ($c) {
            return new SuperSimpleValidationFactory(
                $c->get('rule-map')
            );
        });

        $this->register(ValidationInterface::class, function ($c) {
            return new Validation(
                $c->get("validation-factory")
            );
        });

        $this->register(TemplateInterface::class, function () {
            return new Templates();
        });

        $this->register(LoaderInterface::class, function ($c) {
            return new PHPFileLoader($c->get("config_dir"));
        });
    }
}