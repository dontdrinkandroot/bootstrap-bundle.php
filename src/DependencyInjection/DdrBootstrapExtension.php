<?php

namespace Dontdrinkandroot\BootstrapBundle\DependencyInjection;

use Dontdrinkandroot\Common\Asserted;
use Knp\Bundle\MenuBundle\KnpMenuBundle;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class DdrBootstrapExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new PhpFileLoader($container, new FileLocator(__DIR__ . '/../../config/services'));
        $loader->load('services.php');

        $bundles = Asserted::array($container->getParameter('kernel.bundles'));
        if (in_array(KnpMenuBundle::class, $bundles, true)) {
            $loader->load('knp_menu.php');
        }

        if (in_array(KnpPaginatorBundle::class, $bundles, true)) {
            $container->setParameter(
                'knp_paginator.template.pagination',
                '@KnpPaginator/Pagination/bootstrap_v5_pagination.html.twig'
            );
            $container->setParameter(
                'knp_paginator.template.filtration',
                '@KnpPaginator/Pagination/bootstrap_v5_filtration.html.twig'
            );
            $container->setParameter(
                'knp_paginator.template.sortable',
                '@KnpPaginator/Pagination/bootstrap_v5_fa_sortable_link.html.twig'
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container): void
    {
        $bundles = Asserted::array($container->getParameter('kernel.bundles'));

        if (in_array(TwigBundle::class, $bundles, true)) {
            $container->prependExtensionConfig('twig', [
                'form_themes' => ['@DdrBootstrap/Form/bootstrap_5_horizontal_layout.html.twig']
            ]);
        }
    }
}
