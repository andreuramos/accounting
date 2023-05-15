<?php

namespace App\Invoice\Application\Command;

use App\Shared\Domain\ValueObject\Id;

class CreateInvoiceCommand
{
    public function __construct(
        public readonly Id $incomeId
    ) {
    }
}
