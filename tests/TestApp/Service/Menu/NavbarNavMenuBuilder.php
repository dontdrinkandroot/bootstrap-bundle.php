<?php

namespace Dontdrinkandroot\BootstrapBundle\Tests\TestApp\Service\Menu;

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

        return $menu;
    }
}
