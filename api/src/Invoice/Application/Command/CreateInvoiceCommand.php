<?php

namespace App\Invoice\Application\Command;

use App\Shared\Domain\ValueObject\Id;
use App\User\Domain\Entity\User;

class CreateInvoiceCommand
{
    public function __construct(
        public readonly User $user,
        public readonly Id $incomeId,
    ) {
    }
}
