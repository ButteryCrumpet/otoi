<?php

require "app/vendor/autoload.php";

use \Otoi\Otoi;

if (!function_exists("_")) {
    function _($string) {
        return $string;
    }
}

Otoi::start();
