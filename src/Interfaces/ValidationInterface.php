<?php

namespace Otoi\Interfaces;

interface ValidationInterface
{
    public function validate(ValidatableInterface $validatable);
}