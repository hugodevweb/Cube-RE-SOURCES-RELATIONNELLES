<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\IndexRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(EntityManagerInterface $entityManager, IndexRepository $indexRepository): Response
    {
        return $this->render('index/index.html.twig', [
            'carousel' => $indexRepository->carousel($entityManager),
        ]);
    }

    #[Route('/a-propos', name: 'a-propos')]
    public function aboutUs(): Response
    {
        return $this->render('footer_pages/about_us.html.twig');
    }

    #[Route('/mentions-legales', name: 'mentions')]
    public function importContact(): Response
    {
        return $this->render('footer_pages/mentions.html.twig');
    }

    #[Route('/donnees-personnelles', name: 'donnees-perso')]
    public function donneesPerso(): Response
    {
        return $this->render('footer_pages/donnees_perso.html.twig');
    }

    #[Route('/cookies', name: 'cookies')]
    public function cookies(): Response
    {
        return $this->render('footer_pages/cookies.html.twig');
    }

    #[Route('/plan-du-site', name: 'sitemap')]
    public function sitemap(): Response
    {
        return $this->render('footer_pages/sitemap.html.twig');
    }
}
