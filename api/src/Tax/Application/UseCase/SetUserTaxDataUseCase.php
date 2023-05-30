<?php

namespace App\Tax\Application\UseCase;

use App\Invoice\Domain\Entity\Business;
use App\Invoice\Domain\Model\BusinessRepositoryInterface;
use App\Shared\Domain\ValueObject\Id;
use App\Tax\Application\Command\SetUserTaxDataCommand;
use App\Tax\Domain\ValueObject\Address;

class SetUserTaxDataUseCase
{
    public function __construct(
        private readonly BusinessRepositoryInterface $businessRepository,
    ) {
    }

    public function __invoke(SetUserTaxDataCommand $command): void
    {
        if (!$command->taxName) {
            throw new \InvalidArgumentException();
        }
        $address = new Address(
            $command->taxAddressStreet,
            $command->taxAddressZipCode,
        );

        $business = new Business(
            new Id(null),
            $command->taxName,
            $command->taxName,
            $command->taxNumber,
            $address
        );

        $this->businessRepository->save($business);
    }
}
