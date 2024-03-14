<?php

namespace App\Tests;

use App\Entity\User;
use App\Entity\Article;

use Doctrine\ORM\Mapping\Id;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DataBaseConnectionTest extends WebTestCase
{
    private $entityManager;
    private $client;


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

    public function testDataBaseConnectionSuccess(): void
    {
        //Requeter la base de données pour vérifier si la base est accessible
        $testUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'example@example.com']);
        // Vérifier si l'utilisateur a été correctement enregistré
        $this->assertEquals('John', $testUser->getNom());
    }
    protected function tearDown(): void
    {
        parent::tearDown();
        
        // Rollback the transaction after each test
        if ($this->entityManager != null) {
            $this->entityManager->close();
            $this->entityManager = null;
        }
    }
}
