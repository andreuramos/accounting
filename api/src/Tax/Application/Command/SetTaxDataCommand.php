<?php

namespace App\Tax\Application\Command;

class SetTaxDataCommand
{
    public function __construct(
        public readonly string $taxName,
        public readonly string $taxNumber,
        public readonly string $taxAddressStreet,
        public readonly string $taxAddressZipCode,
    ) {
    }
}
