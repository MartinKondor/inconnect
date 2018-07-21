<?php

namespace App\Tests\Controller;

use App\Entity\ICUser;
use App\Form\UserSignUpType;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testIndex()
    {
        $this->client->request('GET', '/');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testSignupPage()
    {
        $crawler = $this->client->request('GET', '/signup');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $link = $crawler->selectLink('Log in')->link();
        $crawler = $this->client->click($link);

        @$path = end(explode('/', $crawler->getUri()));
        $this->assertEquals('login', $path);

        $crawler = $this->client->request('GET', '/signup');
        $link = $crawler->selectLink('terms and conditions.')->link();
        $crawler = $this->client->click($link);

        @$path = end(explode('/', $crawler->getUri()));
        $this->assertEquals('terms', $path);
    }

    public function testLoginPage()
    {
        $crawler = $this->client->request('GET', '/login');
        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());

        $link = $crawler->selectLink('Create an account')->link();
        $crawler = $this->client->click($link);

        @$path = end(explode('/', $crawler->getUri()));
        $this->assertEquals('signup', $path);
    }
}