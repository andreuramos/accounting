<?php

namespace App\Invoice\Application\Command;

class RenderInvoiceCommand
{
    public function __construct(
        public readonly int $accountId,
        public readonly string $invoiceNumber,
    ) {
    }
}
