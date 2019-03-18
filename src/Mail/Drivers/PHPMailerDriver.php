<?php

namespace Otoi\Mail\Drivers;

use Otoi\Mail\DriverInterface;
use Otoi\Mail\EmailAddress;
use Otoi\Mail\Exceptions\ConfigurationException;
use Otoi\Mail\Exceptions\MailException;
use Otoi\Mail\Exceptions\SendFailException;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;
use Psr\Http\Message\UploadedFileInterface;


class PHPMailerDriver implements DriverInterface
{
    private $config;

    private static $defaults =[
        "html" => true,
        "driver" => "mail",
        "lang" => "jp",
        "charset" => "UTF-8",
        "encoding" => "base64",
        "host" => "",
        "smtpAuth" => false,
        "username" => "",
        "password" => "",
        "port" => 25,
        "smtpSecure" => 'tls'
    ];

    public function __construct($config = [])
    {
        $this->config = array_merge(static::$defaults, $config);
    }


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
    public function send($to, EmailAddress $from, $subject, $body, $cc = [], $bcc = [], $files = [])
    {
        $mail = new PHPMailer(true);
        $mail->setLanguage($this->config["lang"]);
        $mail->CharSet = $this->config["charset"];
        $mail->Encoding = $this->config["encoding"];
        $mail->isHTML($this->config["html"]);

        if ($this->config["driver"] === "sendmail") {
            $mail->isSendmail();
        }

        if ($this->config["driver"] === "smtp") {
            $mail->isSMTP();
            $mail->SMTPDebug = 2;
            $mail->Host = $this->config["host"];
            if ($this->config["smtpAuth"]) {
                $mail->SMTPAuth = $this->config["smtpAuth"];
                $mail->Username = $this->config["username"];
                $mail->Password = $this->config["password"];
            }
            $mail->Port = $this->config["PORT"];
        }

        try {
            $mail->setFrom($from->getAddress(), $from->getName());
        } catch (Exception $e) {
            throw new ConfigurationException($e->getMessage());
        }


        if (!is_array($to)) {
            $to = [$to];
        }

        $mail->Subject = $subject;
        $mail->Body = $body;

        foreach ($to as $email) {
            $mail->addAddress($email->getAddress(), $email->getName());
        }

        foreach ($cc as $email) {
            $mail->addCC($email->getAddress(), $email->getName());
        }

        foreach ($bcc as $email) {
            $mail->addBCC($email->getAddress(), $email->getName());
        }

        foreach ($files as $file) {
            if ($file->getStream()->isSeekable()) {
                $file->getStream()->rewind();
            }
            $mail->addStringAttachment($file->getStream()->getContents(), $file->getClientFilename());
        }


        try {
            return $mail->send();
        } catch (Exception $e) {
            throw new SendFailException($e->getMessage());
        }
    }

}
