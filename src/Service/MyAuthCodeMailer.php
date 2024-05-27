<?php

namespace App\Service;

use Scheb\TwoFactorBundle\Model\Email\TwoFactorInterface;
use Scheb\TwoFactorBundle\Mailer\AuthCodeMailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;

class MyAuthCodeMailer implements AuthCodeMailerInterface
{
    private $mailer;

    public function __construct(MailerInterface $mailer)
    {
        $this->mailer = $mailer;
    }

    public function sendAuthCode(TwoFactorInterface $user): void
    {
        $authCode = $user->getEmailAuthCode();
        $email = (new TemplatedEmail())
            ->from(new Address('contact@oneclick-dev.fr', '(Re)Sources'))
            ->to($user->getEmail())
            ->subject('Code de vérification pour (Re)Sources')
            ->htmlTemplate('Security/auth_code.html.twig')
            ->context([
                'authCode' => $authCode,
                'user' => $user,
            ]);

        $this->mailer->send($email);
    }
}