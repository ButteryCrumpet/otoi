<?php

namespace Otoi\Validation;


interface ValidationResultInterface
{
    public function passed();

    public function failed();

    public function errors();

    public function validated();
}