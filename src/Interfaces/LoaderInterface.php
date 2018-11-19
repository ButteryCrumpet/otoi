<?php

namespace Otoi\Interfaces;

use Otoi\Models\Form;

interface LoaderInterface
{
    public function load($name): Form;

    public function all(): array;

    public function list(): array;
}