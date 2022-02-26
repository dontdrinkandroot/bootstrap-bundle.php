<?php

namespace Dontdrinkandroot\BootstrapBundle\DependencyInjection;

use Knp\Bundle\MenuBundle\KnpMenuBundle;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;
use Symfony\Bundle\TwigBundle\TwigBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class DdrBootstrapExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container): void
    {
        $bundles = $container->getParameter('kernel.bundles');

        if (in_array(TwigBundle::class, $bundles, true)) {
            $container->prependExtensionConfig('twig', [
                'form_themes' => ['@DdrBootstrap/Form/bootstrap_5_horizontal_layout.html.twig']
            ]);
        }

        if (in_array(KnpPaginatorBundle::class, $bundles, true)) {
            $container->prependExtensionConfig(
                'knp_paginator',
                [
                    'template' => [
                        'pagination' => '@KnpPaginator/Pagination/bootstrap_v5_pagination.html.twig',
                        'sortable'   => '@KnpPaginator/Pagination/bootstrap_v5_fa_sortable_link.html.twig',
                        'filtration' => '@KnpPaginator/Pagination/bootstrap_v5_filtration.html.twig'
                    ]
                ]
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container): void
    {
        $configuration = new Configuration();
        $config = $this->processConfiguration($configuration, $configs);

        $loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/../Resources/config'));
        $loader->load('services.yaml');

        $bundles = $container->getParameter('kernel.bundles');
        if (in_array(KnpMenuBundle::class, $bundles)) {
            $loader->load('services_knp_menu.yaml');
        }
    }
}
