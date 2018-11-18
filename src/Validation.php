<?php

namespace Otoi;

use Otoi\Factories\SuperSimpleValidationFactory;

class Validation implements ValidationInterface
{
    private $validators = [];
    private $errors = [];

    public function __construct($conf, SuperSimpleValidationFactory $factory)
    {
        foreach ($conf as $name => $rules) {
            $this->validators[$name] = $factory->build($rules);
        }
    }

    public function validate($data)
    {
        foreach ($this->validators as $name => $validator) {
            $value = array_key_exists($name, $data)
                ? $data[$name]
                : null;
            if (!$this->validators[$name]->validate($value)) {
                $this->errors[$name] = $this->validators[$name]->getErrorMessages();
            }
        }

        return $this->allValid();
    }

    public function getErrors()
    {
        return $this->errors;
    }

    public function hasError($name)
    {
        return isset($this->errors[$name]);
    }

    public function errorsOf($name)
    {
        return isset($this->errors[$name]) ? $this->errors[$name] : [];
    }

    public function allValid()
    {
        return count($this->errors) === 0;
    }
}