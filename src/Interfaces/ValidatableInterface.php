<?php

namespace Otoi\Interfaces;

interface ValidatableInterface
{
    public function getValidation();

    public function getValue();

    public function setValid();

    public function isValid();
}