<?php

namespace App\Form;

use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\TwoFactorFormRendererInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class GoogleAuthenticatorFormRendererWithQR implements TwoFactorFormRendererInterface
{
    private $nativeFormRenderer;
    private $tokenStorage;
    private $schebGoogleAuthenticator;
    private $twig;

    public function __construct(\Twig\Environment $twig,TwoFactorFormRendererInterface $nativeFormRenderer, TokenStorageInterface $tokenStorage, $schebGoogleAuthenticator)
    {   
        $this->twig = $twig;
        $this->nativeFormRenderer = $nativeFormRenderer;
        $this->tokenStorage = $tokenStorage;
        $this->schebGoogleAuthenticator = $schebGoogleAuthenticator;
    }

    public function renderForm(Request $request, array $templateVars): Response
    {
         // Get the QR code content for the user from the TokenStorage
         $user = $this->tokenStorage->getToken()->getUser();
         $qrCodeContent = $this->schebGoogleAuthenticator->getQRContent($user);
 
         // Render the form using the specified template
         $content = $this->twig->render('Security/2fa_form.html.twig', array_merge($templateVars, ['qrCodeContent' => $qrCodeContent]));
 
         // Create a new Response object
         $response = new Response();
 
         // Set the content of the response
         $response->setContent($content);
 
         return $response;
    }
}