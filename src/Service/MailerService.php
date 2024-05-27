<?php

namespace App\Service;

use Psr\Log\LoggerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class MailerService extends AbstractController
{
    private $mailer;
    private $logger;

    public function __construct(MailerInterface $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }

    /**
     * @Route("/sendEmailRequest", name="send_email", methods={"POST"})
     */
    public function sendEmailRequest(Request $request): Response
    {
        $to = $request->request->get('to');
        $subject = $request->request->get('subject');
        $content = $request->request->get('content');

        $this->sendEmail($to, $subject, $content);

        return new Response('Email envoyé avec succès.');
    }

    public function sendEmail(string $to, string $subject, string $content): void
    {
        $email = (new Email())
            ->from('contact@oneclick-dev.fr')
            ->to('hugo.garnier.pro@gmail.com')
            //->cc('cc@example.com')
            //->bcc('bcc@example.com')
            //->replyTo('fabien@example.com')
            //->priority(Email::PRIORITY_HIGH)
            ->subject($subject)
            ->text($content)
            ->html('<p>See Twig integration for better HTML integration!</p>');

        try {
            $this->logger->debug('Attempting to send email to ' . $to);
            $this->mailer->send($email);
            $this->logger->info('Email sent successfully to ' . $to);
        } catch (\Exception $e) {
            $this->logger->error('Error sending email to ' . $to . ': ' . $e->getMessage());
        }
    }
}