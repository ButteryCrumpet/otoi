<?php

namespace Otoi\Validation;


class ValidationResult implements ValidationResultInterface
{
    private $success;
    private $errors;
    private $validated;

    public function __construct($success, array $errors = [], array $validated = [])
    {
        $this->success = $success;
        $this->errors = $errors;
        $this->validated = $validated;
    }

    public function passed(): bool
    {
        return $this->success;
    }

    public function failed(): bool
    {
        return !$this->success;
    }

    public function errors(): array
    {
        return $this->errors;
    }

    public function validated(): array
    {
        return $this->validated;
    }
}