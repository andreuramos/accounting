<?php

namespace App\Invoice\Application\Command;

class RenderInvoiceCommand
{
    public function __construct(
        private readonly int $accountId,
    ) {
    }
}
