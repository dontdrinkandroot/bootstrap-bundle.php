<?php

namespace Dontdrinkandroot\BootstrapBundle\Tests\Acceptance;

use Dontdrinkandroot\BootstrapBundle\Tests\TestApp\Model\RoutePath;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class DropdownMenuActionTest extends WebTestCase
{
    public function testHtml(): void
    {
        $client = self::createClient();
        $crawler = $client->request(Request::METHOD_GET, RoutePath::DROPDOWN_MENU);
        self::assertResponseStatusCodeSame(200);

        $expectedHtml = <<<HTML
<ul class="dropdown-menu">
    <li>
        <a class="dropdown-item" href="/alerts">
            <span>Link</span>
        </a>
    </li>
    <li>
        <h6 class="dropdown-header">Header</h6>
    </li>
    <li>
        <a class="dropdown-item" href="/alerts">
            <span class="bi bi-bell"></span>
            <span>Link with Icon</span>
        </a>
    </li>
    <li>
        <hr class="dropdown-divider" />
    </li>
    <li>
        <a class="dropdown-item text-danger" href="/alerts">
            <span>Link with Icon After</span>
            <span class="bi bi-plus"></span>
        </a>
    </li>
</ul>

HTML;
        self::assertEquals($expectedHtml, $client->getResponse()->getContent());
    }
}
