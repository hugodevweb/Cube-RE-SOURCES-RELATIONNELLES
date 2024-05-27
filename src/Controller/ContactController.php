<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use PHPMailer\PHPMailer\PHPMailer;

class ContactController extends AbstractController
{
    #[Route('/contact', name: 'app_contact', methods: ['GET', 'POST'])]
    public function index(Request $request): Response
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $name = $_POST['name'];
            $email = $_POST['email'];
            $subject = $_POST['subject'];
            $message = $_POST['message'];

            // Validation et envoi de l'email (par exemple)
            if (!empty($name) && !empty($email) && !empty($message) && !empty($subject)) {
                // Logique d'envoi d'email
                $mail = new PHPMailer(true);
                $mail->isSMTP();
                $mail->Host = 'smtp.ethereal.email';
                $mail->SMTPAuth = true;
                $mail->Username = 'valentine.cartwright8@ethereal.email';
                $mail->Password = 'VVTnKfyeXEujKr36Ks';
                $mail->SMTPSecure = 'tls';
                $mail->Port = 587;
                $mail->setFrom('valentine.cartwright8@ethereal.email', 'Valentine Cartwright');
                $mail->Subject = $subject;
                $mail->addAddress($email, 'Nom Destinataire');
                $mail->Body = $message;
                $mail->send();
            }
        }
        return $this->render('contact/index.html.twig', [
        ]);
    }
}
