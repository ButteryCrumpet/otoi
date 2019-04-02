<?php

namespace Otoi\Functions;


/**
 * @param string $key
 * @param string $default
 * @return string
 */
function getenv_or($key, $default = "") {
    $val = getenv($key);
    return $val === false ? $default : $val;
}