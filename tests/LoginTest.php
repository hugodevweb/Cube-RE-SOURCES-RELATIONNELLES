<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class LoginTest extends KernelTestCase
{
    public function testSomething(): void
    {
        $kernel = self::bootKernel();

        //create a test code to t
        $client = static::createClient();
        




        // $routerService = static::getContainer()->get('router');
        // $myCustomService = static::getContainer()->get(CustomService::class);
    }
}
