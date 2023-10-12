<?php

namespace App\UseCase\EmitInvoice;

use App\Domain\Address;
use App\Domain\Business;
use App\Domain\BusinessRepositoryInterface;
use App\Domain\Id;
use App\Domain\Income;
use App\Domain\IncomeRepositoryInterface;
use App\Domain\Invoice;
use App\Domain\InvoiceLine;
use App\Domain\InvoiceLineRepositoryInterface;
use App\Domain\InvoiceNumber;
use App\Domain\InvoiceRepositoryInterface;
use App\Domain\Money;

class EmitInvoiceUseCase
{
    public function __construct(
        private readonly IncomeRepositoryInterface $incomeRepository,
        private readonly BusinessRepositoryInterface $businessRepository,
        private readonly InvoiceRepositoryInterface $invoiceRepository,
        private readonly InvoiceNumberGenerator $invoiceNumberGenerator,
        private readonly InvoiceLineRepositoryInterface $invoiceLineRepository,
    ) {
    }

    public function __invoke(EmitInvoiceCommand $command): InvoiceNumber
    {
        $receiverBusiness = $this->getReceiverBusinessId($command);
        $emitterBusiness = $this->businessRepository->getByUserIdOrFail($command->user->id());
        $invoiceNumber = ($this->invoiceNumberGenerator)($emitterBusiness);

        $income = new Income(
            new Id(null),
            $command->user->accountId(),
            new Money($command->invoiceAmount, 'EUR'),
            "invoice " . $invoiceNumber,
            $command->date,
        );
        $incomeId = $this->incomeRepository->save($income);

        $invoice = new Invoice(
            new Id(null),
            $invoiceNumber,
            $incomeId,
            $emitterBusiness->id,
            $receiverBusiness->id,
            new \DateTime(),
        );
        $invoiceId = $this->invoiceRepository->save($invoice);

        foreach ($command->invoiceLines as $invoiceLine) {
            $product = $invoiceLine['concept'];
            $quantity = 1;
            $amount = $invoiceLine['amount'];
            $line = new InvoiceLine(
                $invoiceId,
                $product,
                $quantity,
                new Money($amount),
            );
            $this->invoiceLineRepository->save($line);
        }

        return $invoiceNumber;
    }

    private function getReceiverBusinessId(EmitInvoiceCommand $command): Business
    {
        $business = $this->businessRepository->getByTaxNumber($command->customerTaxNumber);
        if (null !== $business) {
            return $business;
        }

        $business = new Business(
            new Id(null),
            $command->customerName,
            $command->customerTaxName,
            $command->customerTaxNumber,
            new Address(
                $command->customerTaxAddress,
                $command->customerTaxZipCode
            )
        );
        $this->businessRepository->save($business);

        return $business;
    }
}
