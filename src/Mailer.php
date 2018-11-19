<?php

namespace Otoi;

use Otoi\Interfaces\TemplateInterface;
use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    private $templateEngine;

    public function __construct(TemplateInterface $view)
    {
        $this->templateEngine = $view;
    }

    public function send($fields, $files)
    {
        $configs = $this->getConfigs($fields);

        foreach ($configs as $conf) {
                $mail = new PHPMailer(true);
                $mail->setLanguage('jp');
                $mail->CharSet = 'UTF-8';
                $mail->Encoding = 'base64';

                if (!is_array($conf['to'])) {
                    $conf['to'] = [$conf['to']];
                }
                foreach ($conf["to"] as $to) {
                    $toMail = isset($fields[$to]) ? $fields[$to] : $to;
                    $mail->addAddress($toMail);
                }

                if (isset($conf['from'])) {
                    if (!is_array($conf['from'])) {
                        $conf['from'] = [$conf['from']];
                    }
                    $email = $conf['from'][0];
                    $email = isset($fields[$email]) ? $fields[$email] : $email;
                    $name = isset($conf['from'][1]) ? $conf['from'][1] : null;
                    $name = isset($fields[$name]) ? $fields[$name] : $name;
                    $mail->setFrom($email, $name);
                }

                if (isset($conf["bcc"])) {
                    if (!is_array($conf['bcc'])) {
                        $conf['bcc'] = [$conf['bcc']];
                    }
                    foreach ($conf['bcc'] as $bcc) {
                        $bcc = isset($fields[$bcc]) ? $fields[$bcc] : $bcc;
                        if (filter_var($bcc, FILTER_VALIDATE_EMAIL)) {
                            $mail->addBCC($bcc);
                        }
                    }
                }

                if (isset($conf["cc"])) {
                    if (!is_array($conf['cc'])) {
                        $conf['cc'] = [$conf['cc']];
                    }
                    foreach ($conf['cc'] as $cc) {
                        $cc = isset($fields[$cc]) ? $fields[$cc] : $cc;
                        if (filter_var($cc, FILTER_VALIDATE_EMAIL)) {
                            $mail->addCC($cc);
                        }
                    }
                }

                $mail->Subject = $conf['subject'];
                $mail->Body = $this->templateEngine->render(
                    $conf['template'],
                    $fields
                );
                if (isset($conf["attachments"])) {
                    foreach ($conf['attachments'] as $attachment) {
                        if (array_key_exists($attachment, $files)) {
                            $file = $files[$attachment];
                            $stream = $file->getStream();
                            $stream->rewind();
                            $filename = $file->getClientFilename();
                            $mail->addStringAttachment(
                                $stream->getContents(),
                                $filename ? $filename : "attachment"
                            );
                        }
                    }
                }
                $mail->send();
        }
    }
}
