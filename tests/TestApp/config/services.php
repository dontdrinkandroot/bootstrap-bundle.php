<?php

namespace Dontdrinkandroot\BootstrapBundle\Tests\TestApp\Config;

use Dontdrinkandroot\BootstrapBundle\Tests\TestApp\Service\Menu\MenuBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->defaults()
        ->autoconfigure()
        ->autowire();

    $services->load('Dontdrinkandroot\BootstrapBundle\Tests\TestApp\Controller\\', '../Controller')
        ->tag('controller.service_arguments');

    $services->set(MenuBuilder::class)
        ->args([
            service('knp_menu.factory')
        ])
        ->tag('knp_menu.menu_builder', ['method' => 'createDropdownMenu', 'alias' => 'ddr.bootstrap.test.dropdown'])
        ->tag('knp_menu.menu_builder', ['method' => 'createNavMenu', 'alias' => 'ddr.bootstrap.test.nav'])
        ->tag('knp_menu.menu_builder', ['method' => 'createNavbarNavMenu', 'alias' => 'ddr.bootstrap.test.navbar_nav'])
        ->tag('knp_menu.menu_builder', ['method' => 'createNavbarMenu', 'alias' => 'ddr.bootstrap.test.navbar'])
        ->tag(
            'knp_menu.menu_builder',
            ['method' => 'createButtonGroupMenu', 'alias' => 'ddr.bootstrap.test.button_group']
        );
};

