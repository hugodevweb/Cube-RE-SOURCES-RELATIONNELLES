<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserAccountCrawlerTest extends WebTestCase
{
    public function testSomething(): void
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/compte/new');

        $this->assertResponseIsSuccessful();
        $form = $crawler->selectButton('Sauvegarder')->form();

        // Remplissez les champs du formulaire
        $form['form[nom]'] = 'John';
        $form['form[prenom]'] = 'Doe';
        $form['form[pseudo]'] = 'johndoedu77';
        $form['form[email]'] = 'example@example.com';
        $form['form[password][first]'] = 'your_password';
        $form['form[password][second]'] = 'your_password';

        $client->submit($form);

    }
}
