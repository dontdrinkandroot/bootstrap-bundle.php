<?php

namespace Dontdrinkandroot\BootstrapBundle\Menu;

use Knp\Menu\ItemInterface;

trait Bs5DropdownTrait
{
    protected function addDropdownMenu(
        ItemInterface $item,
        string $name,
        bool $alignEnd = false,
        array $options = [],
    ): ItemInterface {
        return $item->addChild($name, $options)
            ->setExtra(Bs5DropdownMenuRenderer::EXTRA_DROPDOWN, true)
            ->setExtra(Bs5DropdownMenuRenderer::EXTRA_ALIGN_END, $alignEnd);
    }
}
