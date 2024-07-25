<?php

namespace App\Repository;

use App\Entity\Categorie;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

class IndexRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, '');
    }

    public function carousel(EntityManagerInterface $entityManager) 
    {
        $article_par_categorie = [];
        $addedArticles = [];
        foreach ($entityManager->getRepository(Categorie::class)->findAll() as $categorie) {
            $articles = $categorie->getArticles()->toArray();
            $one_article = false;
            if ($articles) {
                foreach (array_reverse($categorie->getArticles()->toArray()) as $article) {
                    if (!$one_article && !in_array($article->getId(), $addedArticles)) {
                        $article_par_categorie[$categorie->getNom()] = [
                            'article' => $article,
                            'imageUrl' => $categorie->getImageUrl(),  // Assurez-vous que cette méthode est disponible
                            'titre' => $article->getTitre(),           // Inclure le titre de l'article
                            'id' => $article->getId()                   // Inclure l'ID de l'article pour le lien
                        ];
                        $addedArticles[] = $article->getId();
                        $one_article = true;
                    }
                }
            }
        }
        return $article_par_categorie;
    }

    public function getLastArticles(EntityManagerInterface $entityManager)
    {
        return $entityManager->createQuery('
            SELECT a
            FROM App\Entity\Article a
            ORDER BY a.created_at DESC
        ')
        ->setMaxResults(3)
        ->getResult();
    }

    public function getLastRessources(EntityManagerInterface $entityManager)
    {
        return $entityManager->createQuery('
            SELECT r
            FROM App\Entity\Ressource r
            ORDER BY r.created_at DESC
        ')
        ->setMaxResults(3)
        ->getResult();
    }
}