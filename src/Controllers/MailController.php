<?php

namespace Otoi\Controllers;

use Otoi\Mail\DriverInterface;
use Otoi\Repositories\FormRepository;
use Otoi\Repositories\MailRepository;
use Otoi\Sessions\SessionInterface;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;

class MailController
{

    private $formRepo;
    private $mailer;
    private $mailRepo;
    private $session;

    public function __construct(
        DriverInterface $mailer,
        FormRepository $formRepository,
        MailRepository $mailConfigLoader,
        SessionInterface $session
    ) {
        $this->mailer = $mailer;
        $this->formRepo = $formRepository;
        $this->mailRepo = $mailConfigLoader;
        $this->session = $session;
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
            } catch (\Exception $e) {
                // log
                throw $e;
            }
        }

        $this->session->condemn();

        return $response
            ->withStatus(303)
            ->withHeader("Location", $form->getFinal());
    }
}