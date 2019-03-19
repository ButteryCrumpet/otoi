<?php

namespace Otoi\Controllers;

use Otoi\Mail\DriverInterface;
use Otoi\Mail\Exceptions\MailException;
use Otoi\Repositories\FormRepository;
use Otoi\Repositories\MailRepository;
use Otoi\Sessions\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Log\LoggerInterface;

class MailController
{

    private $formRepo;
    private $mailer;
    private $mailRepo;
    private $session;
    private $logger;

    public function __construct(
        DriverInterface $mailer,
        FormRepository $formRepository,
        MailRepository $mailConfigLoader,
        SessionInterface $session,
        LoggerInterface $logger
    ) {
        $this->mailer = $mailer;
        $this->formRepo = $formRepository;
        $this->mailRepo = $mailConfigLoader;
        $this->session = $session;
        $this->logger = $logger;
    }

    public function mail(ServerRequestInterface $request, ResponseInterface $response, $args)
    {
        $formName = isset($args["form"]) ? $args["form"] : "default";
        $form = $this->formRepo->load($formName);
        $mails = $this->mailRepo->load($formName);

        $data = (array)$request->getParsedBody();

        foreach ($mails as $mail) {
            try {
                $mail->send($data, $this->mailer);
            } catch (MailException $e) {
                $this->logError($e);
            }
        }

        $this->session->condemn();

        return $response
            ->withStatus(303)
            ->withHeader("Location", $form->getFinal());
    }

    private function logError(MailException $e)
    {
        $message = sprintf(_("Mail send failed. %s - %s"), get_class($e), $e->getMessage());
        $context = [
            "code" => $e->code(),
            "file" => $e->getFile(),
            "line" => $e->getLine(),
            "trace" => $e->getTraceAsString()
        ];
        $this->logger->warning($message, $context);
    }
}