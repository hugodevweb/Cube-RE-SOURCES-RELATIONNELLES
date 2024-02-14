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
}
