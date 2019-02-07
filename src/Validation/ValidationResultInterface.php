<?php

namespace Otoi\Validation;


interface ValidationResultInterface
{
    public function passed(): bool;

    public function failed(): bool;

    public function errors(): array;

    public function validated(): array;
}