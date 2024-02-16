<?php

namespace App\Tests;

use App\Entity\Article;
use App\Entity\User;
use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ArticleTest extends WebTestCase {

    private $entityManager;

    protected function setUp(): void
    {
        parent::setUp();

        // Démarrer le client et récupérer le conteneur
        $client = static::createClient();
        $this->entityManager = $client->getContainer()
            ->get('doctrine')
            ->getManager();

            $user = new User();
            $user->setEmail('example@example.com');
            $user->setPassword('password16253');
            $user->setRoles(['CITOYEN']);
            $user->setNom('John');
            $user->setPrenom('Doe');
            $user->setPseudo('johndoedu77');

            // Enregistrez l'utilisateur dans la base de données
            $this->entityManager->persist($user);
            $this->entityManager->flush();
    }

    public function testCreateArticleSuccess(): void
    {
        // Commencer une transaction
        $this->entityManager->beginTransaction();

        try {
            $article = new Article();
            $article->setTitre('Exemple titre');
            $article->setCreatedAt(new \DateTimeImmutable);
            $article->setCorps('edrftyuiolpmkjhgfdsxcfvgbhnj');
            $article->setNombreVu('5');

            $savedUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'example@example.com']);

            $article->setUser($savedUser);

            // Enregistrez l'article dans la base de données
            $this->entityManager->persist($article);
            $this->entityManager->flush();

            // Récupérez l'article de la base de données pour vérifier s'il a été correctement enregistré
            $savedArticle = $this->entityManager->getRepository(Article::class)->findOneBy(['titre' => 'Exemple titre']);
            
            $this->assertInstanceOf(Article::class, $savedArticle);
            $this->assertEquals((new \DateTimeImmutable())->getTimestamp(), $savedArticle->getCreatedAt()->getTimestamp());
            $this->assertEquals('edrftyuiolpmkjhgfdsxcfvgbhnj', $savedArticle->getCorps());
            $this->assertEquals('5', $savedArticle->getNombreVu());
            $this->assertEquals($savedUser, $savedArticle->getUser());

        } 
        
        finally {
            // Annuler la transaction pour nettoyer la base de données
            $this->entityManager->rollback();
        }
    }

    protected function tearDown(): void
    {
        parent::tearDown();

        // Annuler la transaction après chaque test
        if ($this->entityManager != null) {
            $this->entityManager->close();
            $this->entityManager = null;
        }
    }
}