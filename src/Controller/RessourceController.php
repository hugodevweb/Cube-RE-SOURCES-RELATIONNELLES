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
    

    // #[Route('/deleteRessource/{id}', name: 'delete_ressource', methods: ['GET'])]
    // public function DeleteRessource(int $id, EntityManagerInterface $entityManager): Response
    // {
    //     $repoA = $entityManager->getRepository(Ressource::class);
    //     $ressource = $repoA->find($id);
    //     $entityManager->remove($ressource);
    //     $entityManager->flush();

    //     return $this->redirectToRoute('home');
    // }

    // #[Route('/{id}', name: 'show_ressource')]
    // public function show(EntityManagerInterface $entityManager, int $id, CommentaireRepository $commentaireRepository): Response
    // {
    //     $ressource = $entityManager->getRepository(Ressource::class)->find($id);
    //     $ressource->setNombreVu($ressource->getNombreVu() + 1);
    //     $entityManager->persist($ressource);
    //     $entityManager->flush();
    //     return $this->render('ressource/show.html.twig', [
    //         'ressource' => $ressource,
    //         'commentairesParent' => $commentaireRepository->findCommentsParents($ressource),
    //         'commentairesEnfant' => $commentaireRepository->findCommentsChilds($ressource),
    //     ]);
    // }
}
