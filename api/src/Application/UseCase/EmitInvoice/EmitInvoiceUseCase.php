<?php

namespace App\Application\UseCase\EmitInvoice;

use App\Domain\Entities\Business;
use App\Domain\Entities\Income;
use App\Domain\Entities\Invoice;
use App\Domain\Entities\InvoiceAggregate;
use App\Domain\Repository\BusinessRepositoryInterface;
use App\Domain\Repository\IncomeRepositoryInterface;
use App\Domain\Repository\InvoiceAggregateRepositoryInterface;
use App\Domain\ValueObject\Address;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\InvoiceLine;
use App\Domain\ValueObject\InvoiceNumber;
use App\Domain\ValueObject\Money;
use App\Domain\ValueObject\Percentage;

class EmitInvoiceUseCase
{
    public function __construct(
        private readonly IncomeRepositoryInterface $incomeRepository,
        private readonly BusinessRepositoryInterface $businessRepository,
        private readonly InvoiceNumberGenerator $invoiceNumberGenerator,
        private readonly InvoiceAggregateRepositoryInterface $invoiceAggregateRepository,
    ) {
    }

    public function __invoke(EmitInvoiceCommand $command): InvoiceNumber
    {
        $receiverBusiness = $this->getOrCreateReceiverBusiness($command);
        $emitterBusiness = $this->businessRepository->getByUserIdOrFail($command->user->id());
        $invoiceNumber = ($this->invoiceNumberGenerator)($emitterBusiness);

        $invoice = new Invoice(
            new Id(null),
            $invoiceNumber,
            $emitterBusiness->id,
            $receiverBusiness->id,
            new \DateTime(),
        );

        $invoiceLines = [];
        foreach ($command->invoiceLines as $invoiceLine) {
            $product = $invoiceLine['concept'];
            $quantity = 1;
            $amount = $invoiceLine['amount'];
            $invoiceLines[] = new InvoiceLine(
                $product,
                $quantity,
                new Money($amount),
                new Percentage($invoiceLine['vat_percent']),
            );
        }
        $invoiceAggregate = new InvoiceAggregate(
            $invoice,
            $invoiceLines,
            $emitterBusiness,
            $receiverBusiness,
        );
        $invoiceId = $this->invoiceAggregateRepository->save($invoiceAggregate);

        $income = new Income(
            new Id(null),
            $command->user->accountId(),
            new Money($command->invoiceAmount, 'EUR'),
            "invoice " . $invoiceNumber,
            $command->date,
            $invoiceId,
        );
        $this->incomeRepository->save($income);

        return $invoiceNumber;
    }

    private function getOrCreateReceiverBusiness(EmitInvoiceCommand $command): Business
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
        return $this->businessRepository->getByTaxNumber(
            $command->customerTaxNumber,
        );
    }
}
