<?php

namespace App\Invoice\Infrastructure\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RenderInvoiceCommand extends Command
{
    public function __construct()
    {
        parent::__construct('invoice:render');
    }

    protected function configure()
    {
        $this->addArgument('accountId', InputArgument::REQUIRED, "Account ID of the Invoice");
        $this->addArgument('invoiceNumber', InputArgument::REQUIRED, 'Invoice Number');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $accountId = $input->getArgument('accountId');
        $invoiceNumber = $input->getArgument('invoiceNumber');
        $output->write("Rendering Invoice $invoiceNumber of account $accountId");
        return Command::SUCCESS;
    }
}
