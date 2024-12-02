<?php

namespace Dontdrinkandroot\BootstrapBundle;

use Override;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class DdrBootstrapBundle extends Bundle
{
    #[Override]
    public function getPath(): string
    {
        return dirname(__DIR__);
    }
}
