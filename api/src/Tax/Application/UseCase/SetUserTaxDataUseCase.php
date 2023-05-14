<?php

namespace App\Tax\Application\UseCase;

use App\Tax\Application\Command\SetUserTaxDataCommand;
use App\Tax\Domain\Aggregate\TaxDataAggregate;
use App\Tax\Domain\Model\TaxDataAggregateRepositoryInterface;
use App\Tax\Domain\ValueObject\Address;

class SetUserTaxDataUseCase
{
    public function __construct(
        private readonly TaxDataAggregateRepositoryInterface $taxDataAggregateRepository
    ) {
    }

    public function __invoke(SetUserTaxDataCommand $command): void
    {
        $address = new Address(
            $command->taxAddressStreet,
            $command->taxAddressZipCode,
        );

        $taxData = new TaxDataAggregate(
            $command->user->id(),
            $command->taxName,
            $command->taxNumber,
            $address
        );

        $this->taxDataAggregateRepository->save($taxData);
    }
}
