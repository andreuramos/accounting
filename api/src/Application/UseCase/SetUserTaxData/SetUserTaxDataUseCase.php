<?php

namespace App\Application\UseCase\SetUserTaxData;

use App\Domain\Address;
use App\Domain\Business;
use App\Domain\BusinessRepositoryInterface;
use App\Domain\Id;
use App\Domain\UserRepositoryInterface;

class SetUserTaxDataUseCase
{
    public function __construct(
        private readonly BusinessRepositoryInterface $businessRepository,
        private readonly UserRepositoryInterface $userRepository,
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

        $this->userRepository->linkBusinessToUser(
            $command->user->id(),
            $command->taxNumber,
        );
    }
}
