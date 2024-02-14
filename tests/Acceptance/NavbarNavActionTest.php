<?php

namespace Dontdrinkandroot\BootstrapBundle\Tests\Acceptance;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class NavbarNavActionTest extends WebTestCase
{
    public function testHtml(): void
    {
        $client = self::createClient();
        $crawler = $client->request(Request::METHOD_GET, '/navbar');
        self::assertResponseStatusCodeSame(200);

        $expectedHtml = <<<HTML
<ul class="navbar-nav">
    <li class="nav-item">
        <a class="nav-link">
            <span>Text</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="/alerts" class="nav-link">
            <span>Link</span>
        </a>
    </li>
    <li class="nav-item">
        <a href="/alerts" class="nav-link">
            <span class="bi bi-bell"></span>
            <span>Link with Icon</span>
        </a>
    </li>
    <li class="nav-item">
        <a class="nav-link text-danger" href="/alerts">
            <span>Link with Icon After</span>
            <span class="bi bi-plus"></span>
        </a>
    </li>
    <li class="nav-item dropdown">
        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
            <span>Dropdown</span>
        </a>
    <ul class="dropdown-menu">
        <li>
            <a class="dropdown-item" href="/alerts">
                <span>Item 1</span>
            </a>
        </li>
        <li>
            <hr class="dropdown-divider" />
        </li>
        <li>
            <a class="dropdown-item" href="/alerts">
                <span>Item 2</span>
            </a>
        </li>
    </ul>
    </li>
</ul>

HTML;

        self::assertEquals($expectedHtml, $client->getResponse()->getContent());

    }
}
