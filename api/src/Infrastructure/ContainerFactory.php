<?php

namespace App\Infrastructure;

use DI\Container;
use DI\ContainerBuilder;

class ContainerFactory
{
    private const DEFINITIONS_FOLDER = __DIR__ . '/../../config/definitions/';

    public static function create(): Container
    {
        $interfaces = include self::DEFINITIONS_FOLDER . 'interfaces.php';
        $objects = include self::DEFINITIONS_FOLDER . 'objects.php';

        $builder = new ContainerBuilder();
        $builder->addDefinitions($interfaces);
        $builder->addDefinitions($objects);

        return $builder->build();
    }
}
