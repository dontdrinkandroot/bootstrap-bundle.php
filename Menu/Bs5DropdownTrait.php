<?php

namespace Dontdrinkandroot\BootstrapBundle\Menu;

use Knp\Menu\ItemInterface;

trait Bs5DropdownTrait
{
    protected function addDropdownMenu(ItemInterface $item, string $name, bool $alignEnd = false): ItemInterface
    {
        return $item->addChild($name)
            ->setExtra(Bs5NavbarRenderer::DROPDOWN, true)
            ->setExtra(Bs5NavbarRenderer::ALIGN_END, $alignEnd);
    }
}
