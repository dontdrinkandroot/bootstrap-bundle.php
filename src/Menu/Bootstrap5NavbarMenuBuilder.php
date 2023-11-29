<?php

namespace Dontdrinkandroot\BootstrapBundle\Menu;

use Dontdrinkandroot\BootstrapBundle\Model\ItemExtra;
use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class Bootstrap5NavbarMenuBuilder
{
    public function __construct(protected readonly FactoryInterface $factory)
    {
    }

    protected function addDropdownMenu(ItemInterface $item, string $name, bool $alignEnd = false): ItemInterface
    {
        return $item->addChild($name)
            ->setExtra(ItemExtra::DROPDOWN, true)
            ->setExtra(ItemExtra::ALIGN_END, $alignEnd);
    }
}
