<?php

namespace Otoi\Models;

/**
 * Class MailConfig
 * @package Otoi\Models
 */
class MailConfig
{
    /**
     * @var string
     */
    private $to;
    /**
     * @var string
     */
    private $from;
    /**
     * @var string
     */
    private $subject;
    /**
     * @var string
     */
    private $template;
    /**
     * @var EmailAddress[]
     */
    private $cc;
    /**
     * @var EmailAddress[]
     */
    private $bcc;

    public function __construct(
        EmailAddress $to,
        EmailAddress $from,
        $subject,
        $template,
        array $cc,
        array $bcc,
        $condition
    ) {
        $this->to = $to;
        $this->from = $from;
        $this->subject = $subject;
        $this->template = $template;
        $this->cc = $cc;
        $this->bcc = $bcc;
        $this->condition = $condition;
    }

    /**
     * @return EmailAddress[]
     */
    public function getCc(): array
    {
        return $this->cc;
    }

    /**
     * @return EmailAddress[]
     */
    public function getBcc(): array
    {
        return $this->bcc;
    }

    /**
     * @return EmailAddress
     */
    public function getTo(): EmailAddress
    {
        return $this->to;
    }

    /**
     * @return EmailAddress
     */
    public function getFrom(): EmailAddress
    {
        return $this->from;
    }

    /**
     * @return string
     */
    public function getSubject(): string
    {
        return $this->subject;
    }

    /**
     * @return string
     */
    public function getTemplate(): string
    {
        return $this->template;
    }

    /**
     * @return mixed
     */
    public function getCondition()
    {
        return $this->condition;
    }
    /**
     * @var mixed
     */
    private $condition;
}