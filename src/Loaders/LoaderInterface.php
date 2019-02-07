<?php

namespace Otoi\Interfaces;

interface LoaderInterface
{
    public function load($name);

    public function all(): array;

    public function list(): array;
}