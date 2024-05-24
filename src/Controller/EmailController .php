<?php
// src/Controller/EmailController.php
namespace App\Controller;

use App\Service\MailerService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class EmailController extends AbstractController
{
    private $mailerService;

    public function __construct(MailerService $mailerService)
    {
        $this->mailerService = $mailerService;
    }

    /**
     * @Route("/send-email", name="send_email")
     */
    public function sendEmail(): Response
    {
        $this->mailerService->sendEmail('destinataire@example.com', 'Sujet de l\'email', 'Contenu de l\'email');

        return new Response('Email envoyé avec succès.');
    }

    /**
     * @Route("/email-form", name="email_form")
     */
    public function emailForm(): Response
    {
        return $this->render('email/form.html.twig');
    }
}
