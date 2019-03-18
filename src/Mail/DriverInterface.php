<?php

namespace Otoi\Mail;


use Otoi\Mail\Exceptions\MailException;
use Psr\Http\Message\UploadedFileInterface;

interface DriverInterface
{
    /**
     * @param EmailAddress|EmailAddress[] $to
     * @param EmailAddress $from
     * @param string $subject
     * @param string $body
     * @param UploadedFileInterface[] $files
     * @param EmailAddress[] $cc
     * @param EmailAddress[] $bcc
     * @return bool
     * @throws MailException
     */
    public function send($to, EmailAddress $from, $subject, $body, $cc = [], $bcc = [], $files = []);
}