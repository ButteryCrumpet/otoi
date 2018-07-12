<?php

namespace Otoi;

use PHPMailer\PHPMailer\PHPMailer;

class Mailer
{
    private $configs;
    private $templateEngine;

    public function __construct($configs, $view)
    {
        $this->configs = $configs;
        $this->templateEngine = $view;
    }

    /**
     * @param string[] $fields
     * @param \Psr\Http\Message\UploadedFileInterface[] $files
     * @return boolean
     */
    public function send($fields, $files)
    {
        $mail = new PHPMailer(true);
        $mail->setLanguage('jp');
        $mail->CharSet = 'UTF-8';

        foreach ($this->configs as $conf) {
            try {

                if (!is_array($conf['to'])) {
                    $conf['to'] = [$conf['to']];
                }
                foreach ($conf["to"] as $to) {
                    if (isset($fields[$to])) {
                        $to = $fields[$to];
                    }
                    $mail->addAddress($to);
                }

                if (isset($conf['from'])) {
                    if (!is_array($conf['from'])) {
                        $conf['from'] = [$conf['from']];
                    }
                    $mail->setFrom(
                        $conf['from'][0],
                        isset($conf['from'][1]) ? $conf['from'][1] : null
                    );
                }

                if (isset($conf["bcc"])) {
                    if (!is_array($conf['bcc'])) {
                        $conf['bcc'] = [$conf['bcc']];
                    }
                    foreach ($conf['bcc'] as $bcc) {
                        if (isset($fields[$bcc])) {
                            $bcc = $fields[$bcc];
                        }
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
                        if (isset($fields[$cc])) {
                            $cc = $fields[$cc];
                        }
                        if (filter_var($cc, FILTER_VALIDATE_EMAIL)) {
                            $mail->addCC($cc);
                        }
                    }
                }

                $mail->Subject = $conf['subject'];
                $template = $this->templateEngine->build($conf['template'], array("fields" => $fields));
                $mail->Body = $template->render();
                if (isset($conf["attachments"])) {
                    foreach ($conf['attachments'] as $attachment) {
                        if (array_key_exists($attachment, $files)) {
                            $file = $files[$attachment];
                            $stream = $file->getStream();
                            $stream->rewind();
                            $mail->addStringAttachment($stream->getContents(), $file->getClientFilename());
                        }
                    }
                }
                $mail->send();
            } catch (\PHPMailer\PHPMailer\Exception $e) {
                error_log($e->errorMessage());
                return false;
            }
        }
        return true;
    }
}