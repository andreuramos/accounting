<?php

namespace Test\Unit\Invoice\Infrastructure\Command;

use App\Invoice\Infrastructure\Command\RenderInvoiceCommand;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class RenderInvoiceCommandTest extends TestCase
{
    public function test_null_account_fails(): void
    {
        $input = new ArrayInput([
            'accountId' => null,
            'invoiceNumber' => 123,
        ]);
        $command = new RenderInvoiceCommand();

        $resultStatusCode = $command->run($input, new NullOutput());

        $this->assertEquals(Command::INVALID, $resultStatusCode);
    }

    public function test_null_invoice_fails(): void
    {
        $input = new ArrayInput([
            'accountId' => 1,
            'invoiceNumber' => null,
        ]);
        $command = new RenderInvoiceCommand();

        $resultStatusCode = $command->run($input, new NullOutput());

        $this->assertEquals(Command::INVALID, $resultStatusCode);
    }
}
