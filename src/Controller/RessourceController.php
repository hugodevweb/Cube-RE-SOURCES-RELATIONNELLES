<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\RessourceType;

use App\Repository\CommentaireRepository;
use App\Entity\Article;
use App\Entity\Ressource;

#[Route('/ressources')]
class RessourceController extends AbstractController
{
    #[Route('/', name: 'ressources')]
    public function index(EntityManagerInterface $entityManager): Response
    {
        $ressources = $entityManager->getRepository(Ressource::class)->findAll();
        return $this->render('ressource/index.html.twig', [
            'ressources' => $ressources,
        ]);
    }
}
