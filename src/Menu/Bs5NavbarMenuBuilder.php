<?php

namespace Dontdrinkandroot\BootstrapBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class Bs5NavbarMenuBuilder
{
    public function __construct(protected readonly FactoryInterface $factory)
    {
    }

    protected function addDropdownMenu(ItemInterface $item, string $name, bool $alignEnd = false): ItemInterface
    {
        return $item->addChild($name)
            ->setExtra(Bs5DropdownMenuRenderer::EXTRA_DROPDOWN, true)
            ->setExtra(Bs5DropdownMenuRenderer::EXTRA_ALIGN_END, $alignEnd);
    }
}
