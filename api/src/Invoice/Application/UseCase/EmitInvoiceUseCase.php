<?php

namespace App\Invoice\Application\UseCase;

use App\Business\Domain\Entity\Business;
use App\Business\Domain\Model\BusinessRepositoryInterface;
use App\Business\Domain\ValueObject\Address;
use App\Invoice\Application\Command\EmitInvoiceCommand;
use App\Invoice\Domain\Entity\Invoice;
use App\Invoice\Domain\Entity\InvoiceLine;
use App\Invoice\Domain\Model\InvoiceRepositoryInterface;
use App\Invoice\Domain\Service\InvoiceNumberGenerator;
use App\Invoice\Domain\ValueObject\InvoiceNumber;
use App\Shared\Domain\ValueObject\Id;
use App\Transaction\Domain\Entity\Income;
use App\Transaction\Domain\Exception\IncomeNotFoundException;
use App\Transaction\Domain\Model\IncomeRepositoryInterface;
use App\Transaction\Domain\ValueObject\Money;

class EmitInvoiceUseCase
{
    public function __construct(
        private readonly IncomeRepositoryInterface $incomeRepository,
        private readonly BusinessRepositoryInterface $businessRepository,
        private readonly InvoiceRepositoryInterface $invoiceRepository,
        private readonly InvoiceNumberGenerator $invoiceNumberGenerator,
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

        $lines = [];
        foreach ($command->invoiceLines as $invoiceLine) {
            $product = $invoiceLine['concept'];
            $quantity = 1;
            $amount = $invoiceLine['amount'];
            $lines[] = new InvoiceLine(
                $product,
                $quantity,
                new Money($amount),
            );
        }

        $invoice = new Invoice(
            new Id(null),
            $invoiceNumber,
            $incomeId,
            $emitterBusiness->id,
            $receiverBusiness->id,
            new \DateTime(),
            $lines
        );
        $this->invoiceRepository->save($invoice);

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
