<?php

namespace Dontdrinkandroot\BootstrapBundle\Tests\Acceptance;

use Dontdrinkandroot\BootstrapBundle\Tests\TestApp\Model\RoutePath;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;

class ButtonListActionTest extends WebTestCase
{
    public function testHtml(): void
    {
        $client = self::createClient();
        $crawler = $client->request(Request::METHOD_GET, RoutePath::BUTTON_GROUP);
        self::assertResponseStatusCodeSame(200);

        $expectedHtml = <<<HTML
<div>
    <a type="button" href="/alerts" class="btn">
        <span>Button</span>
    </a>
    <a class="btn btn-primary ddr-btn-icon-only" type="button" href="/alerts">
        <span class="bi bi-bell"></span>
    </a>
    <a class="btn btn-secondary" type="button" href="/alerts">
        <span class="bi bi-bell"></span>
        <span>Button with Icon Before and After</span>
        <span class="bi bi-plus"></span>
    </a>
    <button class="btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
        <span>Dropdown</span>
    </button>
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
</div>

HTML;
        self::assertEquals($expectedHtml, $client->getResponse()->getContent());
    }
}
