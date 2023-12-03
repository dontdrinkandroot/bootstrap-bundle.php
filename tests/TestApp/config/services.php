<?php

namespace Dontdrinkandroot\BootstrapBundle\Tests\TestApp\Config;

use Dontdrinkandroot\BootstrapBundle\Tests\TestApp\Service\Menu\NavbarNavMenuBuilder;
use Knp\Menu\ItemInterface;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->defaults()
        ->autoconfigure()
        ->autowire();

    $services->load('Dontdrinkandroot\BootstrapBundle\Tests\TestApp\Controller\\', '../Controller')
        ->tag('controller.service_arguments');

    $services->set(NavbarNavMenuBuilder::class)
        ->args([
            service('knp_menu.factory')
        ]);

    $navBarNavId = 'ddr.bootstrap.test.navbar_nav';
    $services->set($navBarNavId)
        ->class(ItemInterface::class)
        ->factory([service(NavbarNavMenuBuilder::class), 'createMenu'])
        ->tag('knp_menu.menu', ['alias' => $navBarNavId]);
};
