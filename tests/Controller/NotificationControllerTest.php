<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class NotificationControllerTest extends WebTestCase
{
    /**
     * @dataProvider redirectUrlProvider
     */
    public function testPageIsRedirecting($url)
    {
        $client = self::createClient();
        $client->request('POST', $url);

        $this->assertFalse($client->getResponse()->isSuccessful());
    }

    public function redirectUrlProvider()
    {
        yield ['/notification'];
    }
}