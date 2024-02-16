<?php

namespace App\Tests;

use App\Entity\User;
use DAMA\DoctrineTestBundle\Doctrine\DBAL\StaticDriver;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserCrawlerTest extends WebTestCase
{
    private $entityManager;
    private $client;

    protected function setUp(): void
    {
        parent::setUp();

        // Start the client and get the container
        $this->client = static::createClient();
        $this->entityManager = $this->client->getContainer()->get('doctrine')->getManager();

        // Begin a transaction
    }

    public function testCreateUserCrawlerSuccess(): void
    {
        try {
            echo "Test de création d'un utilisateur avec succès \n";
            $crawler = $this->client->request('GET', '/compte/new');

            $this->assertResponseIsSuccessful();
            $form = $crawler->selectButton('Sauvegarder')->form();
            echo "Selection du formulaire \n";
            $form['user[nom]'] = 'John';
            echo "Selection du Nom \n";
            $form['user[prenom]'] = 'Doe';
            echo "Selection du Prenom \n";
            $form['user[pseudo]'] = 'johndoedu77';
            echo "Selection du Pseudo \n";
            $form['user[email]'] = 'exampleCCrawler@example.com';
            echo "Selection du Email \n";
            $form['user[password][first]'] = 'your_password';
            echo "Selection du Password \n";
            $form['user[password][second]'] = 'your_password';
            echo "Selection du Password \n";
            echo $this->entityManager->getConnection()->isTransactionActive();

            $this->client->submit($form);
            echo "Soumission du formulaire\n";
            
            echo $this->entityManager->getConnection()->isTransactionActive();
            // Perform your assertions on the response if necessary
            // $this->assertSelectorTextContains('h1', 'Page de confirmation');
        } finally {
            // Rollback the transaction to leave the database in its initial state
        }
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
