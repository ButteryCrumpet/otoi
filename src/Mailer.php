<?php

namespace Otoi;

use Otoi\Interfaces\TemplateInterface;
use Otoi\Models\MailConfig;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    private $templateEngine;

    public function __construct(TemplateInterface $view)
    {
        $this->templateEngine = $view;
    }

    public function send(MailConfig $config, $fields, $files = array())
    {
        $mail = new PHPMailer(true);
        $mail->setLanguage('jp');
        $mail->CharSet = 'UTF-8';
        $mail->Encoding = 'base64';

        $to = $config->getTo();
        $mail->addAddress($to->getAddress(), $to->getName());
        $from = $config->getFrom();
        $mail->setFrom($from->getAddress(), $from->getName());

        $cc = $config->getCc();
        foreach ($cc as $email) {
            $mail->addCC($email->getAddress(), $email->getName());
        }

        $bcc = $config->getBcc();
        foreach ($bcc as $email) {
            $mail->addCC($email->getAddress(), $email->getName());
        }

        $mail->Subject = $config->getSubject();

        $mail->Body = $this->templateEngine->render(
            $config->getTemplate(),
            ["form" => $fields]
        );

        //if (isset($conf["attachments"])) {
        //    foreach ($conf['attachments'] as $attachment) {
        //        if (array_key_exists($attachment, $files)) {
        //            $file = $files[$attachment];
        //            $stream = $file->getStream();
        //            $stream->rewind();
        //            $filename = $file->getClientFilename();
        //            $mail->addStringAttachment(
        //                $stream->getContents(),
        //                $filename ? $filename : "attachment"
        //            );
        //        }
        //    }
        //}
        return $mail->send();
    }
}
