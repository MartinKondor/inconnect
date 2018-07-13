<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UsersControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testUsersPage()
    {
        $this->client->request('GET', '/u/~john');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testUserPages()
    {
        $this->client->request('GET', '/user/pages');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }

    public function testUserSettings()
    {
        $this->client->request('GET', '/user/settings');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }
}