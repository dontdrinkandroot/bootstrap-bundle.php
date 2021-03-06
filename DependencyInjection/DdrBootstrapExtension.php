<?php

namespace Dontdrinkandroot\BootstrapBundle\DependencyInjection;

use Doctrine\Bundle\DoctrineBundle\DoctrineBundle;
use Knp\Bundle\MenuBundle\KnpMenuBundle;
use Knp\Bundle\PaginatorBundle\KnpPaginatorBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\PrependExtensionInterface;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class DdrBootstrapExtension extends Extension implements PrependExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function prepend(ContainerBuilder $container)
    {
        $bundles = $container->getParameter('kernel.bundles');
        if (in_array(KnpPaginatorBundle::class, $bundles)) {
            $container->prependExtensionConfig(
                'knp_paginator',
                [
                    'template' => [
                        'pagination' => '@KnpPaginator/Pagination/twitter_bootstrap_v4_pagination.html.twig',
                        'sortable'   => '@DdrBootstrap/KnpPaginator/bs_4_sortable.html.twig',
                        'filtration' => '@DdrBootstrap/KnpPaginator/bs_4_filtration.html.twig'
                    ]
                ]
            );
        }
    }

    /**
     * {@inheritdoc}
     */
    public function load(array $configs, ContainerBuilder $container)
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
