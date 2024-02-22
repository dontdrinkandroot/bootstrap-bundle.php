<?php

namespace Dontdrinkandroot\BootstrapBundle\Config;

use Dontdrinkandroot\BootstrapBundle\Menu\Bootstrap5ButtonListRenderer;
use Dontdrinkandroot\BootstrapBundle\Menu\Bootstrap5DropdownMenuRenderer;
use Dontdrinkandroot\BootstrapBundle\Menu\Bootstrap5NavbarNavRenderer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;

use function Symfony\Component\DependencyInjection\Loader\Configurator\param;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(Bootstrap5DropdownMenuRenderer::class)
        ->args([
            service('knp_menu.matcher'),
            service('translator'),
            param('kernel.charset'),
        ])
        ->tag('knp_menu.renderer', ['alias' => 'ddr_bootstrap5_dropdown_menu']);

    $services->set(Bootstrap5NavbarNavRenderer::class)
        ->args([
            service('knp_menu.matcher'),
            service('translator'),
            service(Bootstrap5DropdownMenuRenderer::class),
            param('kernel.charset'),
        ])
        ->tag('knp_menu.renderer', ['alias' => 'ddr_bootstrap5_navbar_nav']);

    $services->set(Bootstrap5ButtonListRenderer::class)
        ->args([
            service('knp_menu.matcher'),
            service('translator'),
            service(Bootstrap5DropdownMenuRenderer::class),
            param('kernel.charset'),
        ])
        ->tag('knp_menu.renderer', ['alias' => 'ddr_bootstrap5_button_list']);
};
