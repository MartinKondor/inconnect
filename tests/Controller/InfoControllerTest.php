<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class InfoControllerTest extends WebTestCase
{
    /**
     * @dataProvider urlProvider
     */
    public function testPageIsSuccessful($url)
    {
        $client = self::createClient();
        $client->request('GET', $url);

        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }

    public function urlProvider()
    {
        yield ['/privacy'];
        yield ['/terms'];
        yield ['/cookies'];
        yield ['/help'];
    }

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
        yield ['/events'];
        yield ['/findfriends'];
        yield ['/about'];
    }
}
