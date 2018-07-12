<?php

namespace Otoi;

interface ValidationInterface
{
    public function validate($data);

    public function getErrors();

    public function hasError($name);

    public function errorsOf($name);

    public function allValid();
}