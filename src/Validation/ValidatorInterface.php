<?php

namespace Otoi\Validation;

interface ValidatorInterface
{
    public function validate(ValidatableInterface $data): ValidationResultInterface;
}