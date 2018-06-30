<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function testHome()
    {
        $client = static::createClient();
        $client->request('GET', '/home');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}