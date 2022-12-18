<?php

namespace Dontdrinkandroot\BootstrapBundle\Config;

use Dontdrinkandroot\BootstrapBundle\Menu\Bs5DropdownMenuRenderer;
use Dontdrinkandroot\BootstrapBundle\Menu\Bs5NavbarRenderer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator) {
    $services = $configurator->services();

    $services->set(Bs5DropdownMenuRenderer::class, Bs5DropdownMenuRenderer::class)
        ->args([
            service('translator'),
            param('kernel.charset')
        ])
        ->tag('knp_menu.renderer', ['alias' => 'ddr_bs5_dropdown_menu']);

    $services->set(Bs5NavbarRenderer::class, Bs5NavbarRenderer::class)
        ->args([
            service('knp_menu.matcher'),
            service('translator'),
            service(Bs5DropdownMenuRenderer::class),
            param('knp_menu.renderer.list.options'),
            param('kernel.charset')
        ])
        ->tag('knp_menu.renderer', ['alias' => 'ddr_navbar_bs5']);
};
