<?php

namespace Otoi\Interfaces;


interface StrategyInterface
{
    public function single($args);

    public function list(): array;
}