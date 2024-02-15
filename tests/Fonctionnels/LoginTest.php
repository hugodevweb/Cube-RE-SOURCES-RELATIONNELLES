<?php

namespace App\Tests\Fonctionnels;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginTest extends WebTestCase
{
    public function testIfLoginIsSuccessful(): void
    {
        $client = static::createClient();

        /** @var UrlGeneratorInterface $url_generator */
        $url_generator = $client->getContainer()->get("router");

        $crawler = $client->request('GET', $url_generator->generate('app_login'));

        // Form

        // $form = $crawler->filter("form[name=]")

        // redirect + home

    }
}
