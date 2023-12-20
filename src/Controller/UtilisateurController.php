<?php

namespace App\Controller;

use App\Repository\UtilisateurRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UtilisateurController extends AbstractController
{
    #[Route('/profil/{id}', name: 'app_utilisateur')]
    public function index(int $id, UtilisateurRepository $utilisateurRepository): Response
    {
        $utilisateur = $utilisateurRepository->find($id);
        return $this->render('utilisateur/index.html.twig', [
            'utilisateur' => $utilisateur,
            'articles' => $utilisateur->getArticles(),
            'role' => $utilisateur->getRole(),
        ]);
    }
}
