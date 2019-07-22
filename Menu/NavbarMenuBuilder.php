<?php

namespace Dontdrinkandroot\BootstrapBundle\Menu;

use Knp\Menu\FactoryInterface;
use Knp\Menu\ItemInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class NavbarMenuBuilder
{
    /**
     * @var FactoryInterface
     */
    private $factory;

    public function __construct(FactoryInterface $factory)
    {
        $this->factory = $factory;
    }

    protected function addDropdownMenu(ItemInterface $item, string $name, bool $alignRight = false): ItemInterface
    {
        return $item->addChild($name)
            ->setExtra('dropdown', true)
            ->setExtra('align_right', $alignRight);
    }

    protected function getFactory(): FactoryInterface
    {
        return $this->factory;
    }
}
