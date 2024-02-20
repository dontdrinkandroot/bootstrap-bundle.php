<?php

namespace Dontdrinkandroot\BootstrapBundle\Tests\Acceptance;

use Dontdrinkandroot\BootstrapBundle\Tests\TestApp\Model\RoutePath;
use Dontdrinkandroot\Common\Asserted;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class AlertsActionTest extends WebTestCase
{
    public function testAlertsRendered(): void
    {
        $client = self::createClient();
        $crawler = $client->request(Request::METHOD_GET, RoutePath::ALERTS);
        self::assertResponseStatusCodeSame(200);

        $html = $crawler->html();

        $alerts = $crawler->filter('.alert');
        self::assertCount(3, $alerts);

        self::assertEquals('Info Alert', $alerts->eq(0)->text());
        self::assertEquals('alert alert-info', trim(Asserted::string($alerts->eq(0)->attr('class'))));
        self::assertEquals('Success Alert', $alerts->eq(1)->text());
        self::assertEquals('alert alert-success', trim(Asserted::string($alerts->eq(1)->attr('class'))));
        self::assertEquals('Error Alert', $alerts->eq(2)->text());
        self::assertEquals('alert alert-danger', trim(Asserted::string($alerts->eq(2)->attr('class'))));
    }
}
