<?php

namespace Otoi\Interfaces;


interface SessionInterface
{
    public function start($args = []): bool;

    public function get($key, $default);

    public function set($key, $value);

    public function condemn();

    public function isCondemned(): bool;

    public function forceDestroy(): bool;
}