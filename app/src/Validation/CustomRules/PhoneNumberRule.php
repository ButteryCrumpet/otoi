<?php

namespace Otoi\Validation\CustomRules;

use SuperSimpleValidation\RuleInterface;
use SuperSimpleValidation\Rules\Regex;

class PhoneNumberRule implements RuleInterface
{
    private $regexRule;

    public function __construct()
    {
        $this->regexRule = new Regex(
            '/^\(?\+?\d{1,4}\)?-?\d{2,4}-?\d{4}$/',
            'Must be valid phone number'
        );
    }

    public function assert($data)
    {
        return $this->regexRule->assert($data);
    }

    public function validate($data)
    {
        return $this->regexRule->validate($data);
    }

    public function getErrorMessages()
    {
        return $this->regexRule->getErrorMessages();
    }
}