<?php

namespace Dontdrinkandroot\BootstrapBundle\Tests\TestApp\Service\Menu;

use Dontdrinkandroot\BootstrapBundle\Model\ItemExtra;
use Dontdrinkandroot\BootstrapBundle\Model\ItemOption;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class NavbarNavMenuBuilder
{
    public function __construct(
        private readonly FactoryInterface $factory
    ) {
    }

    public function createMenu(): ItemInterface
    {
        $menu = $this->factory->createItem('root');

        $menu->addChild('item.text', ['label' => 'Text']);

        $menu->addChild('item.link', ['label' => 'Link', ItemOption::ROUTE => 'test_app.alerts']);

        $menu->addChild('item.link.icon', ['label' => 'Link with Icon', ItemOption::ROUTE => 'test_app.alerts'])
            ->setExtra(ItemExtra::ICON, 'bi bi-bell');

        $menu->addChild('item.link.after', ['label' => 'Link with Icon After', ItemOption::ROUTE => 'test_app.alerts'])
            ->setLinkAttribute('class', 'text-danger')
            ->setExtra(ItemExtra::ICON_AFTER, 'bi bi-plus');

        $dropdown = $menu->addChild('item.dropdown', ['label' => 'Dropdown']);
        $dropdown->addChild('item.dropdown.item1', ['label' => 'Item 1', ItemOption::ROUTE => 'test_app.alerts']);
        $dropdown->addChild('item.dropdown.separator1')
            ->setExtra(ItemExtra::DROPDOWN_DIVIDER, true);
        $dropdown->addChild('item.dropdown.item2', ['label' => 'Item 2', ItemOption::ROUTE => 'test_app.alerts']);

        return $menu;
    }
}
