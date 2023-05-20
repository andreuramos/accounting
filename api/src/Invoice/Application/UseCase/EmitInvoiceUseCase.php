<?php

namespace App\Invoice\Application\UseCase;

use App\Invoice\Application\Command\EmitInvoiceCommand;
use App\Invoice\Domain\Entity\Business;
use App\Invoice\Domain\Model\BusinessRepositoryInterface;
use App\Invoice\Domain\ValueObject\InvoiceNumber;
use App\Shared\Domain\ValueObject\Id;
use App\Tax\Domain\Entity\TaxData;
use App\Tax\Domain\ValueObject\Address;
use App\Transaction\Domain\Exception\IncomeNotFoundException;
use App\Transaction\Domain\Model\IncomeRepositoryInterface;

class EmitInvoiceUseCase
{
    public function __construct(
        private readonly IncomeRepositoryInterface $incomeRepository,
        private readonly BusinessRepositoryInterface $businessRepository,
    ) {
    }
    public function __invoke(EmitInvoiceCommand $command): InvoiceNumber
    {
        $income = $this->incomeRepository->getByIdOrFail($command->incomeId);
        if ($income->userId->getInt() !== $command->user->id()->getInt()) {
            throw new IncomeNotFoundException();
        }

        $businessId = $this->getBusinessId($command);

        return new InvoiceNumber('');
    }

    private function getBusinessId(EmitInvoiceCommand $command): Id
    {
        $business = $this->businessRepository->getByTaxNumber($command->customerTaxNumber);
        if (null !== $business) {
            return $business->id;
        }

        $business = new Business(
            new Id(null),
            $command->customerName,
            new TaxData(
                new Id(null),
                $command->customerTaxName,
                $command->customerTaxNumber,
                new Address(
                    $command->customerTaxAddress,
                    $command->customerTaxZipCode
                )
            )
        );
        return $this->businessRepository->save($business);
    }
}
