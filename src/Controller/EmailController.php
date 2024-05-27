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
     * @Route("/email-form", name="email_form")
     */
    public function emailForm(): Response
    {
        return $this->render('email/form.html.twig');
    }
}
