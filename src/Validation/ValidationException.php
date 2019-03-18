<?php

namespace Otoi\Validation;

use Throwable;

class ValidationException extends \Exception
{
    private $errors = [];

    public function __construct($errors = [], $code = 400, Throwable $previous = null)
    {
        $this->errors = $errors;
        $message = is_array($errors) ? $this->formatErrors($errors) : $errors;
        parent::__construct($message, $code, $previous);
    }

    public function getErrors()
    {
        return $this->errors;
    }

    private function formatErrors(array $errors)
    {
        $str = "Validation Failed: ";
        foreach ($errors as $key => $error) {
            if (is_array($error)) {
                $str .= $this->formatErrors($error);
            } else {
                $str .= $this->formatError($key, $error);
            }
        }
        return $str;
    }

    private function formatError($key, $error)
    {
        return "[$key: $error]";
    }
}