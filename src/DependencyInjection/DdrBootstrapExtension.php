<?php

namespace Dontdrinkandroot\BootstrapBundle\DependencyInjection;

use Dontdrinkandroot\Common\Asserted;
use Knp\Bundle\MenuBundle\KnpMenuBundle;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;

class DdrBootstrapExtension extends Extension
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
    }
}
