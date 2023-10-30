<?php

namespace App\Application\UseCase\SetUserTaxData;

use App\Domain\Entities\Business;
use App\Domain\Repository\BusinessRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Address;
use App\Domain\ValueObject\Id;

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
