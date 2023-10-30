<?php

namespace App\Application\UseCase\SetUserTaxData;

use App\Domain\Entities\User;

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
