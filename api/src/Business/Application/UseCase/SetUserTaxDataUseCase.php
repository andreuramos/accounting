<?php

namespace App\Business\Application\UseCase;

use App\Business\Application\Command\SetUserTaxDataCommand;
use App\Business\Domain\Entity\Business;
use App\Business\Domain\Model\BusinessRepositoryInterface;
use App\Business\Domain\ValueObject\Address;
use App\Shared\Domain\ValueObject\Id;
use App\User\Domain\Model\UserRepositoryInterface;

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
