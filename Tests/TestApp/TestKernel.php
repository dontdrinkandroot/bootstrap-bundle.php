<?php

namespace Dontdrinkandroot\BootstrapBundle\Tests\TestApp;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel;

class TestKernel extends Kernel
{
    use MicroKernelTrait;

    /**
     * {@inheritdoc}
     */
    public function getProjectDir(): string
    {
        return __DIR__;
    }

    /**
     * {@inheritdoc}
     */
    public function getCacheDir(): string
    {
        return sys_get_temp_dir() . '/ddr_bootstrap_bundle/cache/';
    }

    /**
     * {@inheritdoc}
     */
    public function getLogDir(): string
    {
        return sys_get_temp_dir() . '/ddr_bootstrap_bundle/logs/';
    }
}
