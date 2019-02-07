<?php

namespace Otoi\Validation;

use Otoi\Parsers\StringValidationParser;
// Should be moved to validation package?
class Validator implements ValidatorInterface
{
    private $factory;

    public function __construct(StringValidationParser $factory)
    {
        $this->factory = $factory;
    }

    public function validate(ValidatableInterface $validatable): ValidationResultInterface
    {

        if (empty($validatable->rules())) {
            return new ValidationResult(true, [], $validatable->data());
        }

        $errors = [];
        $validated = [];

        $data = $this->flatten($validatable->data());
        $rules = $this->flatten($validatable->rules());

        foreach ($rules as $key => $rule) {
            $value = isset($data[$key]) ? $data[$key] : null;
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
            $validatable->data()
        );
    }

    private function flatten($array, $prefix = '')
    {
        $result = [];
        foreach ($array as $key => $value) {
            if (is_array($value)) {
                $result = $result + $this->flatten($value, $prefix . $key . ".");
            } else {
                $result[$prefix . $key] = $value;
            }
        }
        return $result;
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