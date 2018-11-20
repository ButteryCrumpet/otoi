<?php

namespace Otoi\CustomRules;

use SuperSimpleValidation\RuleInterface;
use SuperSimpleValidation\Rules\Regex;

class JapaneseCharacters implements RuleInterface
{
    private $regexRule;

    public function __construct()
    {
        $this->regexRule = new Regex(
            '/^[\p{Katakana}\p{Hiragana}\p{Han}0-9]+$/u',
            'Must be Katakana, Hiragana or Kanji'
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