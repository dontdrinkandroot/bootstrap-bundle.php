<?php

namespace Dontdrinkandroot\BootstrapBundle\Tests\TestApp\Service\Menu;

use Dontdrinkandroot\BootstrapBundle\Model\ItemExtra;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class DropdownMenuBuilder
{
    public function __construct(
        private readonly FactoryInterface $factory
    ) {
    }

    public function createMenu(): ItemInterface
    {
        $root = $this->factory->createItem('root');

        $root->addChild('link', ['label' => 'Link', 'route' => 'test_app.alerts']);

        $root->addChild('header', ['label' => 'Header'])
            ->setExtra(ItemExtra::DROPDOWN_HEADER, true);

        $root->addChild('link.icon', ['label' => 'Link with Icon', 'route' => 'test_app.alerts'])
            ->setExtra(ItemExtra::ICON_BEFORE, 'bi bi-bell');

        $root->addChild('divider')
            ->setExtra(ItemExtra::DROPDOWN_DIVIDER, true);

        $root->addChild('link.icon.after', ['label' => 'Link with Icon After', 'route' => 'test_app.alerts'])
            ->setExtra(ItemExtra::ICON_AFTER, 'fa fa-plus')
            ->setLinkAttribute('class', 'text-danger');

        return $root;
    }
}
