<?php

namespace Otoi;

use Otoi\Factories\SuperSimpleValidationFactory;
use Otoi\Interfaces\ValidatableInterface;
use Otoi\Interfaces\ValidationInterface;

class Validation implements ValidationInterface
{
    private $factory;

    public function __construct(SuperSimpleValidationFactory $factory)
    {
        $this->factory = $factory;
    }

    public function validate(ValidatableInterface $validatable)
    {
        $validator = $this->factory->build($validatable->getValidation());
        if ($validator->validate($validatable->getValue())) {
            $validatable->setValid();
        } else {
            if ($validatable instanceof ErrorAwareInterface) {
                $validatable->setErrors($validator->getErrorMessages());
            }
        }
    }
}