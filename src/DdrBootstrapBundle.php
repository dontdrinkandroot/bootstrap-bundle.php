<?php

namespace Dontdrinkandroot\BootstrapBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class DdrBootstrapBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function getPath(): string
    {
        return dirname(__DIR__);
    }
}
