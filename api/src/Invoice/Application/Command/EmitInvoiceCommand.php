<?php

namespace App\Invoice\Application\Command;

use App\Shared\Domain\ValueObject\Id;
use App\User\Domain\Entity\User;

class EmitInvoiceCommand
{
    public function __construct(
        public readonly User $user,
        public readonly Id $incomeId,
        public readonly string $customerName,
        public readonly string $customerTaxName,
        public readonly string $customerTaxNumber,
        public readonly string $customerTaxAddress,
        public readonly string $customerTaxZipCode,
        public readonly \DateTime $date,
        public readonly int $amount,
        public readonly string $concept,
    ) {
    }
}
