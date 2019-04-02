<?php

namespace Otoi\Http\Sessions;


interface SessionInterface
{
    public function start($args = []);

    public function get($key, $default);

    public function set($key, $value);

    public function forget($key);

    public function condemn();

    public function condemned();

    public function forceDestroy();

    public function flash($key, $value);

    public function ageFlash();

    //public function removeFlash();

    public function getFlash($key = null, $default = null);
}