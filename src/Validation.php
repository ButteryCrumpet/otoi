<?php

namespace Otoi;

use Otoi\Interfaces\ErrorAwareInterface;
use Otoi\Interfaces\ValidatableInterface;
use Otoi\Interfaces\ValidationInterface;
use Otoi\Parsers\StringValidationParser;

class Validation implements ValidationInterface
{
    private $factory;

    public function __construct(StringValidationParser $factory)
    {
        $this->factory = $factory;
    }

    public function validate(ValidatableInterface $validatable)
    {
        $validator = $this->factory->parse($validatable->getValidation());
        if ($validator->validate($validatable->getValue())) {
            $validatable->setValid();
        } else {
            if ($validatable instanceof ErrorAwareInterface) {
                $validatable->setErrors($validator->getErrorMessages());
            }
        }
    }
}