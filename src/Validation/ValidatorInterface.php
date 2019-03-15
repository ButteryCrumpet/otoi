<?php

namespace Otoi\Validation;

interface ValidatorInterface
{
    /**
     * @param array $rules
     * @param array $data
     * @return ValidationResultInterface
     */
    public function validate($rules, $data);
}