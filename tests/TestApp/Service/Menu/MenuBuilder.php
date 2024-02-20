<?php

namespace Dontdrinkandroot\BootstrapBundle\Tests\TestApp\Service\Menu;

use Dontdrinkandroot\BootstrapBundle\Model\ItemExtra;
use Dontdrinkandroot\BootstrapBundle\Model\ItemOption;
use Dontdrinkandroot\BootstrapBundle\Tests\TestApp\Model\RouteName;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class MenuBuilder
{
    public function __construct(
        private readonly FactoryInterface $factory
    ) {
    }

    public function createDropdownMenu(): ItemInterface
    {
        $root = $this->factory->createItem('root');

        $this->addDropdownItems($root, 'dropdownmenu');

        return $root;
    }

    public function createNavbarNavMenu(): ItemInterface
    {
        $menu = $this->factory->createItem('root');
        $menu->setAttribute('class', 'additional-class-1 additional-class-2');

        return $this->addNavbarNavItems($menu, 'navbarnav');
    }

    public function createButtonGroupMenu(): ItemInterface
    {
        $root = $this->factory->createItem('root');

        $root->addChild('buttongroup.button', ['label' => 'Button', 'route' => RouteName::ALERTS]);

        $root->addChild('buttongroup.button.icon_only', ['label' => false, 'route' => RouteName::ALERTS])
            ->setAttribute('class', 'btn-primary ddr-btn-icon-only')
            ->setExtra(ItemExtra::ICON, 'bi bi-bell');

        $root->addChild(
            'buttongroup.button.icon.before_and_after',
            ['label' => 'Button with Icon Before and After', 'route' => RouteName::ALERTS]
        )
            ->setAttribute('class', 'btn-secondary')
            ->setExtra(ItemExtra::ICON, 'bi bi-bell')
            ->setExtra(ItemExtra::ICON_AFTER, 'bi bi-plus');

        $dropdown = $root->addChild('buttongroup.dropdown', ['label' => 'Dropdown']);
        $this->addDropdownItems($dropdown, 'buttongroup.dropdown');

        return $root;
    }

    private function addNavbarNavItems(ItemInterface $parent, string $prefix): ItemInterface
    {
        $parent->addChild($prefix . '.navbarnavitem.text', ['label' => 'Text']);

        $parent->addChild($prefix . '.navbarnavitem.link', ['label' => 'Link', ItemOption::ROUTE => RouteName::ALERTS]);

        $parent->addChild(
            $prefix . '.navbarnavitem.link.icon',
            ['label' => 'Link with Icon', ItemOption::ROUTE => RouteName::ALERTS]
        )
            ->setExtra(ItemExtra::ICON, 'bi bi-bell');

        $parent->addChild(
            $prefix . '.navbarnavitem.link.after',
            ['label' => 'Link with Icon After', ItemOption::ROUTE => RouteName::ALERTS]
        )
            ->setLinkAttribute('class', 'text-danger')
            ->setExtra(ItemExtra::ICON_AFTER, 'bi bi-plus');

        $dropdown = $parent->addChild($prefix . '.navbarnavitem.dropdown', ['label' => 'Dropdown']);
        $this->addDropdownItems($dropdown, $prefix . '.navbarnavitem.dropdown');

        return $parent;
    }

    public function addDropdownItems(ItemInterface $parent, string $prefix): ItemInterface
    {
        $parent->addChild($prefix . '.dropdownitem.link', ['label' => 'Link', 'route' => RouteName::ALERTS]);

        $parent->addChild($prefix . '.dropdownitem.header', ['label' => 'Header'])
            ->setExtra(ItemExtra::DROPDOWN_HEADER, true);

        $parent->addChild(
            $prefix . '.dropdownitem.link.icon',
            ['label' => 'Link with Icon', 'route' => RouteName::ALERTS]
        )
            ->setExtra(ItemExtra::ICON_BEFORE, 'bi bi-bell');

        $parent->addChild($prefix . '.dropdownitem.divider')
            ->setExtra(ItemExtra::DROPDOWN_DIVIDER, true);

        $parent->addChild(
            $prefix . '.dropdownitem.link.icon.after',
            ['label' => 'Link with Icon After', 'route' => RouteName::ALERTS]
        )
            ->setExtra(ItemExtra::ICON_AFTER, 'bi bi-plus')
            ->setLinkAttribute('class', 'text-danger');

        return $parent;
    }
}
