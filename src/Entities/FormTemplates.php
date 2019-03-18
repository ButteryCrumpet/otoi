<?php

namespace Otoi\Entities;

/**
 * Class FormTemplates
 * @package Otoi\Models
 */
class FormTemplates
{
    /**
     * @var string
     */
    private $index;
    /**
     * @var string
     */
    private $confirm;

    /**
     * FormTemplates constructor.
     * @param string $index
     * @param string $confirm
     */
    public function __construct($index, $confirm)
    {
        $this->index = $index;
        $this->confirm = $confirm;
    }

    /**
     * @return string
     */
    public function getIndex()
    {
        return $this->index;
    }

    /**
     * @return string
     */
    public function getConfirm()
    {
        return $this->confirm;
    }
}