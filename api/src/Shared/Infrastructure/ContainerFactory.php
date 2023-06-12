<?php

namespace App\Shared\Infrastructure;

use DI\Container;
use DI\ContainerBuilder;

class ContainerFactory
{
    private const DEFINITIONS_FOLDER = __DIR__ . '/../../../config/definitions/';

    public static function create(): Container
    {
        $interfaces = require(self::DEFINITIONS_FOLDER . 'interfaces.php');
        $objects = require(self::DEFINITIONS_FOLDER . 'objects.php');

        $builder = new ContainerBuilder();
        $builder->addDefinitions($interfaces);
        $builder->addDefinitions($objects);

        return $builder->build();
    }
}
