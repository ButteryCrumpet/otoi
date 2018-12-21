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
     * @var string
     */
    private $final;

    /**
     * FormTemplates constructor.
     * @param string $index
     * @param string $confirm
     * @param string|null $final
     */
    public function __construct(string $index, string $confirm, string $final = null)
    {
        $this->index = $index;
        $this->confirm = $confirm;
        $this->final = $final;
    }

    /**
     * @return string
     */
    public function getIndex(): string
    {
        return $this->index;
    }

    /**
     * @return string
     */
    public function getConfirm(): string
    {
        return $this->confirm;
    }

    /**
     * @return string
     */
    public function getFinal(): string
    {
        return $this->final;
    }
}