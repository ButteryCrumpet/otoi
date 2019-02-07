<?php

namespace Otoi\Interfaces;

use Otoi\Entities\Form;

interface FormLoaderInterface extends LoaderInterface
{
    public function load($name): Form;
}