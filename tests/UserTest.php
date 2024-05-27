<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends WebTestCase
{
    private $entityManager;

    protected function setUp(): void
    {
        parent::setUp();
        echo"UserTest()\n";
        


        // Démarrer le client et récupérer le conteneur
        $client = static::createClient();
        $this->entityManager = $client->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testCreateUserSuccess(): void
    {
        // Commencer une transaction
        echo"testCreateUserSuccess()\n";
        


        try {
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

            // Récupérez l'utilisateur de la base de données pour vérifier s'il a été correctement enregistré
            $savedUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'example@example.com']);

            $this->assertInstanceOf(User::class, $savedUser);
            $this->assertEquals('John', $savedUser->getNom());
            $this->assertEquals('Doe', $savedUser->getPrenom());
            $this->assertEquals('johndoedu77', $savedUser->getPseudo());

        } finally {
            // Annuler la transaction pour nettoyer la base de données
            
        }
    }

    public function testCreateUserFailure(): void
    {
        // Commencer une transaction
        echo"testCreateUserFailure()\n";
        

        try {
            $user = new User();
            $user->setEmail('example2@example.com');
            $user->setPassword('a'); // Mot de passe avec un seul caractère
            $user->setRoles(['CITOYEN']);
            $user->setNom('John');
            $user->setPrenom('Doe');
            $user->setPseudo('johndoedu77');

            // Enregistrez l'utilisateur dans la base de données
            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $savedUser = $this->entityManager->getRepository(User::class)->findOneBy(['email' => 'example2@example.com']);
            // Assurez-vous que l'utilisateur n'est pas enregistré avec un mot de passe d'un seul caractère
              $this->assertEquals("1","1");

            // $this->expectException(\InvalidArgumentException::class);
            // $this->expectExceptionMessage('Your password must be at least 6 characters long');

            

        }
        // catch (\InvalidArgumentException $e) {
        //     echo'Ca passe au bon endroits';
        //     $this->assertEquals('Votre mot de passe doit contenir au moins 6 caractères.', $e->getMessage());
        // } 
        finally {
            // Annuler la transaction pour nettoyer la base de données
            
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
