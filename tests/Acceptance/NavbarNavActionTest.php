<?php

namespace Dontdrinkandroot\BootstrapBundle\Tests\Acceptance;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class NavbarNavActionTest extends WebTestCase
{
    public function testAlertsRendered(): void
    {
        $client = self::createClient();
        $crawler = $client->request(Request::METHOD_GET, '/navbar');
        self::assertResponseStatusCodeSame(200);

        $html = $crawler->html();
    }
}
