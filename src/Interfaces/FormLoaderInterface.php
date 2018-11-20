<?php

namespace Otoi\Interfaces;

use Otoi\Models\Form;

interface FormLoaderInterface extends LoaderInterface
{
    public function load($name): Form;
}