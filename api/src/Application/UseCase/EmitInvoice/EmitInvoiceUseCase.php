<?php

namespace App\Application\UseCase\EmitInvoice;

use App\Domain\Entities\Business;
use App\Domain\Entities\Income;
use App\Domain\Entities\Invoice;
use App\Domain\Entities\InvoiceLine;
use App\Domain\Repository\BusinessRepositoryInterface;
use App\Domain\Repository\IncomeRepositoryInterface;
use App\Domain\Repository\InvoiceLineRepositoryInterface;
use App\Domain\Repository\InvoiceRepositoryInterface;
use App\Domain\ValueObject\Address;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\InvoiceNumber;
use App\Domain\ValueObject\Money;

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
