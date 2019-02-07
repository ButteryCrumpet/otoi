<?php

namespace Otoi\Validation;

class ValidationException extends \Exception
{
    private $errors = [];

    public function setErrors(array $errors)
    {
        $this->errors = $errors;
        return $this;
    }

    public function getErrors(): array
    {
        return $this->errors;
    }
}