<?php

namespace Test\Integration\Invoice;

use App\Infrastructure\Command\RenderInvoiceCommand;
use App\Infrastructure\ContainerFactory;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Tester\CommandTester;

class RenderInvoiceCommandTest extends TestCase
{
    public function test_command_returns_ok_status()
    {
        $container = ContainerFactory::create();
        $application = new Application();
        $application->add($container->get(RenderInvoiceCommand::class));
        $command = $application->find('invoice:render');
        $commandTester = new CommandTester($command);

        $commandTester->execute([
            'command' => $command->getName(),
            'accountId' => 1,
            'invoiceNumber' => '20230001',
        ]);

        $this->assertEquals(0, $commandTester->getStatusCode());
    }
}
