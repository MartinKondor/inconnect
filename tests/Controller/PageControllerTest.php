<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class PageControllerTest extends WebTestCase
{
    private $client = null;

    public function setUp()
    {
        $this->client = static::createClient();
    }

    public function testCreatePage()
    {
        $this->client->request('GET', '/pages/create');
        $this->assertEquals(302, $this->client->getResponse()->getStatusCode());
    }
}