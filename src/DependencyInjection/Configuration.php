<?php

namespace Dontdrinkandroot\BootstrapBundle\DependencyInjection;

use Override;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

class Configuration implements ConfigurationInterface
{
    #[Override]
    public function getConfigTreeBuilder(): TreeBuilder
    {
        $treeBuilder = new TreeBuilder('ddr_bootstrap');
        $rootNode = $treeBuilder->getRootNode();

        // @formatter:off
        // @formatter:on

        return $treeBuilder;
    }
}
