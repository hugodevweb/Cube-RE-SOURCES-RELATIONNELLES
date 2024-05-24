<?php

namespace App\Service;

use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

class MailerService
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @Route("/sendEmailRequest", name="send_email", methods={"POST"})
     */
    public function sendEmailRequest(Request $request): Response
    {
        $to = $request->request->get('to');
        $subject = $request->request->get('subject');
        $content = $request->request->get('content');

        $this->mailerService->sendEmail($to, $subject, $content);

        return new Response('Email envoyé avec succès.');
    }

    public function sendEmail(string $to, string $subject, string $content): void
    {
        $email = (new Email())
            ->from('contact@oneclick-dev.FR')
            ->to($to)
            ->subject($subject)
            ->text($content);

        $this->mailer->send($email);
    }

}


