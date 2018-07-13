<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InfoControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testNews()
    {
        $this->client->request('GET', '/news');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testEvents()
    {
        $this->client->request('GET', '/events');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testFindfriends()
    {
        $this->client->request('GET', '/findfriends');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testAbout()
    {
        $this->client->request('GET', '/about');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testPrivacy()
    {
        $this->client->request('GET', '/privacy');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testTerms()
    {
        $this->client->request('GET', '/terms');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testCookies()
    {
        $this->client->request('GET', '/cookies');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testHelp()
    {
        $this->client->request('GET', '/help');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }
}