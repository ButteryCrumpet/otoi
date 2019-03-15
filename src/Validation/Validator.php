<?php

namespace Otoi\Validation;

use Otoi\Collections\FlatHashMap;
use Otoi\Parsers\StringValidationParser;
// Should be moved to validation package?
class Validator implements ValidatorInterface
{
    private $factory;

    /**
     * Validator constructor.
     * @param StringValidationParser $factory
     */
    public function __construct(StringValidationParser $factory)
    {
        $this->factory = $factory;
    }

    /**
     * @inheritdoc
     */
    public function validate($rules, $data)
    {

        if (empty($rules)) {
            return new ValidationResult(true, [], $data);
        }

        $errors = [];
        $validated = [];

        $data = FlatHashMap::fromArray($data);

        foreach ($rules as $key => $rule) {
            $value = isset($data[$key]) ? $data[$key] : "";
            $validator = $this->factory->parse($rule);

            if ($validator->validate($value)) {
                $validated[$key] = $value;
            } else {
                $this->set($errors, $key, $validator->getErrorMessages());
            }
        }

        return new ValidationResult(
            empty($errors),
            $errors,
            $validated
        );
    }

    public static function set(&$array, $key, $value)
    {
        if (is_null($key)) {
            return $array = $value;
        }

        $keys = explode('.', $key);

        while (count($keys) > 1) {
            $key = array_shift($keys);

            if (! isset($array[$key]) || ! is_array($array[$key])) {
                $array[$key] = [];
            }

            $array = &$array[$key];
        }

        $array[array_shift($keys)] = $value;

        return $array;
    }
}