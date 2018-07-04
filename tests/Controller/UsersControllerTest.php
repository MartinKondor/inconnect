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
        $this->client->request('GET', '/u/testclara');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }
}