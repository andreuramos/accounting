<?php

namespace App\Tax\Application\UseCase;

use App\Invoice\Domain\Entity\Business;
use App\Invoice\Domain\Model\BusinessRepositoryInterface;
use App\Shared\Domain\ValueObject\Id;
use App\Tax\Application\Command\SetUserTaxDataCommand;
use App\Tax\Domain\Entity\TaxData;
use App\Tax\Domain\Model\TaxDataAggregateRepositoryInterface;
use App\Tax\Domain\ValueObject\Address;

class SetUserTaxDataUseCase
{
    public function __construct(
        private readonly BusinessRepositoryInterface $businessRepository,
    ) {
    }

    public function __invoke(SetUserTaxDataCommand $command): void
    {
        $address = new Address(
            $command->taxAddressStreet,
            $command->taxAddressZipCode,
        );

        $taxData = new TaxData(
            $command->user->id(),
            $command->taxName,
            $command->taxNumber,
            $address
        );

        $business = new Business(
            new Id(null),
            $command->taxName,
            $taxData
        );

        $this->businessRepository->save($business);
    }
}
