<?php

use App\Infrastructure\Command\RenderInvoiceCommand;
use App\Infrastructure\ContainerFactory;
use Symfony\Component\Console\Application;

require_once __DIR__ . '/../vendor/autoload.php';

$container = ContainerFactory::create();

$application = new Application();
// command list should go to another file, and iterate them
$application->add($container->get(RenderInvoiceCommand::class));
$application->run();
