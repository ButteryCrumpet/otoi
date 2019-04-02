<?php

namespace Otoi\Drivers;


interface DriverInterface
{
    public function single($args);

    public function listing();
}