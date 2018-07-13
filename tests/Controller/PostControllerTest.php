<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PostControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testPostPage()
    {
        $this->client->request('GET', '/p/0');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }
}