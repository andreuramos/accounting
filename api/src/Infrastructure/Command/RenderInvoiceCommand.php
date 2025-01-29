<?php

namespace App\Infrastructure\Command;

use App\Application\UseCase\RenderInvoice\RenderInvoiceCommand as ApplicationCommand;
use App\Application\UseCase\RenderInvoice\RenderInvoiceUseCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class RenderInvoiceCommand extends Command
{
    public function __construct(private readonly RenderInvoiceUseCase $renderInvoiceUseCase)
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

        try {
            $applicationCommand = new ApplicationCommand($accountId, $invoiceNumber);
        } catch (\Throwable $throwable) {
            $output->write($throwable->getMessage());
            return Command::INVALID;
        }

        try {
            ($this->renderInvoiceUseCase)($applicationCommand);
        } catch (\Exception $exception) {
            $output->writeln($exception->getMessage());
            return Command::FAILURE;
        }

        $output->write("Rendering Invoice $invoiceNumber of account $accountId\n");
        return Command::SUCCESS;
    }
}
