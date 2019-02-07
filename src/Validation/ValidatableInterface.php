<?php

namespace Otoi\Validation;


interface ValidatableInterface
{
    public function data(): array;

    public function rules(): array;
}