<?php

namespace Otoi\Parsers;

use SuperSimpleValidation\Logic\LogicNot;
use SuperSimpleValidation\Logic\LogicOr;
use SuperSimpleValidation\RuleInterface;
use SuperSimpleValidation\Rules\Required;
use SuperSimpleValidation\Validator;

class StringValidationParser implements ParserInterface
{
    private $ruleMap;

    public function __construct(array $ruleMap)
    {
        $this->ruleMap = $ruleMap;
    }

    public function parse($config): RuleInterface
    {
        if (!is_string($config)) {
            throw new \InvalidArgumentException(sprintf(
                "Config must be a string, %s was given",
                gettype($config)
            ));
        }

        $rules = [];
        $conf = explode("|", $config);
        foreach ($conf as $rule) {
            $rules[] = $this->parseRule($rule);
        }

        $validator = new Validator($rules);

        if (!in_array("required", $conf)) {
            $notRequired = new LogicNot(
                [new Required(":ignore")],
                ":ignore"
            );
            return new Validator([
                new LogicOr([$notRequired, $validator], ":ignore")
            ]);
        }

        return $validator;
    }

    private function parseRule($string)
    {
        $exploded = explode(":", $string);
        $ruleName = $exploded[0];

        if (!array_key_exists($ruleName, $this->ruleMap)) {
            throw new \InvalidArgumentException(
                sprintf("%s is not a valid rule", $ruleName)
            );
        }
        $args = isset($exploded[1]) ? $this->parseArgs($exploded[1]) : null;
        $rule = $this->ruleMap[$ruleName];
        $message = $ruleName;
        if ($args) {
            return new $rule($args, $message);
        }
        return new $rule($message);
    }

    private function parseArgs($string)
    {
        if (strpos($string, ",") === false) {
            return $string;
        }

        return explode(",", $string);
    }
}