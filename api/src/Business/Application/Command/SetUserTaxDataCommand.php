<?php

namespace App\Business\Application\Command;

use App\User\Domain\Entity\User;

class SetUserTaxDataCommand
{
    public function __construct(
        public readonly User $user,
        public readonly string $taxName,
        public readonly string $taxNumber,
        public readonly string $taxAddressStreet,
        public readonly string $taxAddressZipCode,
    ) {
    }
}
