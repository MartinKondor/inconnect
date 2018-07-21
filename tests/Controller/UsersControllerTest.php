<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UsersControllerTest extends WebTestCase
{
    /**
     * @dataProvider redirectUrlProvider
     */
    public function testPageIsRedirecting($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isRedirect());
        $this->assertEquals(302, $client->getResponse()->getStatusCode());
    }

    public function redirectUrlProvider()
    {
        yield ['/u/john'];
        yield ['/u/pages'];
        yield ['/u/settings'];
    }
}