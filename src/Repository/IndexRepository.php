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
                        $article_par_categorie[$categorie->getNom()] = $article;
                        $addedArticles[] = $article->getId();
                        $one_article = true;
                    }
                }
            }
        }
        return $article_par_categorie;
    }
}