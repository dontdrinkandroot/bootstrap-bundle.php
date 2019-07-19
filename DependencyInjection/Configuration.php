<?php

namespace Dontdrinkandroot\ApiPlatformBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * @author Philip Washington Sorst <philip@sorst.net>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder('ddr_bootstrap');
        $rootNode = $treeBuilder->getRootNode();

        // @formatter:off
        // @formatter:on

        return $treeBuilder;
    }
}
