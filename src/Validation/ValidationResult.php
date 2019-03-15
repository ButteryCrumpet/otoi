<?php

namespace Otoi\Validation;


class ValidationResult implements ValidationResultInterface
{
    private $success;
    private $errors;
    private $validated;

    public function __construct($success, $errors = [], $validated = [])
    {
        $this->success = $success;
        $this->errors = $errors;
        $this->validated = $validated;
    }

    public function passed()
    {
        return $this->success;
    }

    public function failed()
    {
        return !$this->success;
    }

    public function errors()
    {
        return $this->errors;
    }

    public function validated()
    {
        return $this->validated;
    }
}