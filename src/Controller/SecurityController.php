<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Request;
use Scheb\TwoFactorBundle\Security\TwoFactor\Provider\Google\GoogleAuthenticatorInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;

class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(AuthenticationUtils $authenticationUtils, Session $session): Response
    {
        // if ($this->getUser()) {
        //     return $this->redirectToRoute('target_path');
        // }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        $return = ['last_username' => $lastUsername, 'error' => $error];

        if($session->has('message'))
        {
                $message = $session->get('message');
                $session->remove('message'); //on vide la variable message dans la session
                $return['message'] = $message; //on ajoute à l'array de paramètres notre message
        }

        return $this->render('user/forms/login.html.twig', $return);
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    public function removeGoogleAuth(
        Request $request,
        GoogleAuthenticatorInterface $googleAuthenticator,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager // Inject EntityManagerInterface
    )
    {
        // Fetch the user
        $user = $this->getUser();
        if (!$user) {
            // Handle the case where the user is not logged in
            // Redirect to login or handle appropriately
        }
    
        // Retrieve password from the submitted form
        $password = $request->request->get('password');
    
        // Verify the password
        if (!$passwordHasher->isPasswordValid($user, $password)) {
            $this->addFlash('error', 'Mot de passe invalide');
            return new RedirectResponse($this->generateUrl('app_user_show', ['id' => $user->getId()]));
        }
    
        // Generate a new secret
        $secret = $googleAuthenticator->generateSecret();
    
        // Set the secret on the user entity
        $user->setGoogleAuthenticatorSecret(null);
    
        // You might want to persist changes to the database
        $entityManager->persist($user); // Using EntityManagerInterface
        $entityManager->flush();
    
        $this->addFlash('success', 'Authentification à deux facteurs Google désactivée'); // Add the flash message

        return new RedirectResponse($this->generateUrl('app_user_show', ['id' => $user->getId()]));
    }




    //Create addGoogleAuth method
    public function addGoogleAuth(
        Request $request,
        GoogleAuthenticatorInterface $googleAuthenticator,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager // Inject EntityManagerInterface
    )
    {
        // Fetch the user
        $user = $this->getUser();
        if (!$user) {
            // Handle the case where the user is not logged in
            // Redirect to login or handle appropriately
        }
    
        // Retrieve password from the submitted form
        $password = $request->request->get('password');
    
        // Verify the password
        if (!$passwordHasher->isPasswordValid($user, $password)) {
            $this->addFlash('error', 'Mot de passe invalide');
            return new RedirectResponse($this->generateUrl('app_user_show', ['id' => $user->getId()]));
        }
    
        // Generate a new secret
        $secret = $googleAuthenticator->generateSecret();
    
        // Set the secret on the user entity
        $user->setGoogleAuthenticatorSecret($secret);
    
        // You might want to persist changes to the database
        $entityManager->persist($user); // Using EntityManagerInterface
        $entityManager->flush();
    
        // Redirect the user to the app_user_show route
        $this->addFlash('sucessActivate', 'Authentification à deux facteurs Google activée');
        $this->addFlash('qrContent', $secret);
        return new RedirectResponse($this->generateUrl('app_user_show', ['id' => $user->getId()]));
    }
    


    public function getGoogleQrCode(
        Request $request,
        GoogleAuthenticatorInterface $googleAuthenticator,
        UserPasswordHasherInterface $passwordHasher,
        EntityManagerInterface $entityManager // Inject EntityManagerInterface
    )
    {
        // Fetch the user
        $user = $this->getUser();
        if (!$user) {
            // Handle the case where the user is not logged in
            // Redirect to login or handle appropriately
        }
    
        $password = $request->request->get('password');
    
        // Verify the password
        if (!$passwordHasher->isPasswordValid($user, $password)) {
            $this->addFlash('error', 'Mot de passe invalide');
            return new RedirectResponse($this->generateUrl('app_user_show', ['id' => $user->getId()]));
        }

        $qrContent = $googleAuthenticator->getQRContent($user);

        // Store the QR content in the flash bag
        $this->addFlash('qrContent', $qrContent);
    
        return $this->redirectToRoute('app_user_show', ['id' => $user->getId()]);
    }

}
