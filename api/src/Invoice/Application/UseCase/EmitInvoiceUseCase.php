<?php

namespace App\Invoice\Application\UseCase;

use App\Invoice\Application\Command\EmitInvoiceCommand;
use App\Invoice\Domain\Entity\Invoice;
use App\Invoice\Domain\Model\InvoiceRepositoryInterface;
use App\Invoice\Domain\Service\InvoiceNumberGenerator;
use App\Invoice\Domain\ValueObject\InvoiceNumber;
use App\Shared\Domain\ValueObject\Id;
use App\Tax\Domain\Entity\Business;
use App\Tax\Domain\Model\BusinessRepositoryInterface;
use App\Tax\Domain\ValueObject\Address;
use App\Transaction\Domain\Exception\IncomeNotFoundException;
use App\Transaction\Domain\Model\IncomeRepositoryInterface;

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
        $income = $this->incomeRepository->getByIdOrFail($command->incomeId);
        if ($income->userId->getInt() !== $command->user->id()->getInt()) {
            throw new IncomeNotFoundException();
        }

        $receiverBusiness = $this->getReceiverBusinessId($command);
        $emitterBusiness = $this->businessRepository->getByUserIdOrFail($command->user->id());
        $invoiceNumber = ($this->invoiceNumberGenerator)($emitterBusiness);

        $invoice = new Invoice(
            new Id(null),
            $invoiceNumber,
            $income->id,
            $emitterBusiness->id,
            $receiverBusiness->id,
            new \DateTime()
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
