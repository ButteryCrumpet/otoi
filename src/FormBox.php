<?php

namespace Otoi;


use Otoi\Entities\Form;
use Otoi\Interfaces\Box;

class FormBox implements Box
{
    private $item = null;

    public function set($item)
    {
        if (!is_null($this->item)) {
            throw new \RuntimeException("Cannot replace a box item");
        }

        if (!($item instanceof Form)) {
            throw new \InvalidArgumentException("Item must be a " . Form::class . ".");
        }

        $this->item = $item;
    }

    public function get() : Form
    {
        if (is_null($this->item)) {
            throw new \RuntimeException("Attempted to get something out of an empty box");
        }

        return $this->item;
    }

}