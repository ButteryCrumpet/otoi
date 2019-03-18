<?php

namespace Otoi\Csrf;


use Throwable;

class InvalidCsrfException extends \Exception
{
    public function __construct($message = "", $code = 403, Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}