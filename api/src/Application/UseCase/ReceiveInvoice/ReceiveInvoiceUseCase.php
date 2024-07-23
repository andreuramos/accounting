<?php

namespace App\Application\UseCase\ReceiveInvoice;

use App\Domain\Entities\Business;
use App\Domain\Entities\Expense;
use App\Domain\Entities\Invoice;
use App\Domain\Entities\InvoiceLine;
use App\Domain\Exception\InvoiceAlreadyExistsException;
use App\Domain\Repository\BusinessRepositoryInterface;
use App\Domain\Repository\ExpenseRepositoryInterface;
use App\Domain\Repository\InvoiceLineRepositoryInterface;
use App\Domain\Repository\InvoiceRepositoryInterface;
use App\Domain\ValueObject\Address;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\InvoiceNumber;
use App\Domain\ValueObject\Money;

class ReceiveInvoiceUseCase
{
    public function __construct(
        private readonly InvoiceRepositoryInterface $invoiceRepository,
        private readonly BusinessRepositoryInterface $businessRepository,
        private readonly InvoiceLineRepositoryInterface $invoiceLineRepository,
        private readonly ExpenseRepositoryInterface $expenseRepository,
    ) {
    }

    public function __invoke(ReceiveInvoiceCommand $command): void
    {
        $this->guardInvoiceDoesNotYetExist($command);
        $receiver_business = $this->businessRepository->getByUserIdOrFail($command->user->id());
        $emitter_business = $this->getEmitterBusiness($command);

        $invoice = new Invoice(
            new Id(null),
            new InvoiceNumber($command->invoice_number),
            $emitter_business->id,
            $receiver_business->id,
            new \DateTime($command->date),
        );
        $invoice_id = $this->invoiceRepository->save($invoice);

        $invoice_line = new InvoiceLine(
            $invoice_id,
            $command->description,
            1,
            new Money($command->amount),
        );
        $this->invoiceLineRepository->save($invoice_line);

        $expense = new Expense(
            new Id(null),
            $command->user->accountId(),
            new Money($command->amount),
            $command->description,
            new \DateTime($command->date),
            $invoice_id,
        );
        $this->expenseRepository->save($expense);
    }

    private function guardInvoiceDoesNotYetExist(ReceiveInvoiceCommand $command): void
    {
        $invoiceNumber = new InvoiceNumber($command->invoice_number);
        $invoice = $this->invoiceRepository->findByEmitterTaxNumberAndInvoiceNumber(
            $command->provider_tax_number,
            $invoiceNumber,
        );

        if ($invoice instanceof Invoice) {
            throw new InvoiceAlreadyExistsException(
                $command->provider_tax_number,
                $invoiceNumber,
            );
        }
    }

    private function getEmitterBusiness(ReceiveInvoiceCommand $command): Business
    {
        $business = $this->businessRepository->getByTaxNumber($command->provider_tax_number);
        if ($business instanceof Business) {
            return $business;
        }

        $business = new Business(
            new Id(null),
            $command->provider_name,
            $command->provider_tax_name,
            $command->provider_tax_number,
            new Address(
                $command->provider_tax_address,
                $command->provider_tax_zip_code,
            ),
        );
        $this->businessRepository->save($business);

        return $this->businessRepository->getByTaxNumber($command->provider_tax_number);
    }
}
