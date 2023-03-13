<?php

namespace App\Shared\Infrastructure;

use DI\Container;
use DI\ContainerBuilder;

class ContainerFactory
{
    const DEFINITIONS_FOLDER = __DIR__ . '/../../../config/definitions/';

    public static function create(): Container
    {
        $interfaces = require(self::DEFINITIONS_FOLDER . 'interfaces.php');
        $objects = require(self::DEFINITIONS_FOLDER . 'objects.php');
        $jsonValues = file_get_contents(self::DEFINITIONS_FOLDER . 'shared-values.json');
        $values = json_decode($jsonValues, true);

        $builder = new ContainerBuilder();
        $builder->addDefinitions($interfaces);
        $builder->addDefinitions($objects);
        $builder->addDefinitions($values);

        return $builder->build();
    }
}
