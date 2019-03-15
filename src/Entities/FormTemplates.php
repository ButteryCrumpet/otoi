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
     * @var string|null
     */
    private $final;

    /**
     * FormTemplates constructor.
     * @param string $index
     * @param string $confirm
     * @param string|null $final
     */
    public function __construct($index, $confirm, $final = null)
    {
        $this->index = $index;
        $this->confirm = $confirm;
        $this->final = $final;
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

    /**
     * @return string|null
     */
    public function getFinal()
    {
        return $this->final;
    }
}