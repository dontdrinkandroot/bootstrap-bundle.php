<?php

namespace Dontdrinkandroot\BootstrapBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;

class Bs5NavbarMenuBuilder
{
    protected FactoryInterface $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    protected function addDropdownMenu(ItemInterface $item, string $name, bool $alignEnd = false): ItemInterface
    {
        return $item->addChild($name)
            ->setExtra(Bs5NavbarRenderer::EXTRA_DROPDOWN, true)
            ->setExtra(Bs5NavbarRenderer::EXTRA_ALIGN_END, $alignEnd);
    }
}
