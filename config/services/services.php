<?php

namespace Dontdrinkandroot\BootstrapBundle\Config;

use Dontdrinkandroot\BootstrapBundle\Pagination\CountablePaginationExtension;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return function (ContainerConfigurator $configurator): void {
    $services = $configurator->services();

    $services->set(CountablePaginationExtension::class, CountablePaginationExtension::class)
        ->args([
            service(UrlGeneratorInterface::class),
            service(RequestStack::class)
        ])
        ->tag('twig.extension');
};

