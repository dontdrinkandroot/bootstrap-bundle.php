<?php

use ShipMonk\ComposerDependencyAnalyser\Config\Configuration;

return new Configuration()
    ->addPathToExclude(__DIR__ . '/src/Form/Type/DatalistType.php')
    ->addPathToExclude(__DIR__ . '/src/DependencyInjection/DdrBootstrapExtension.php');
