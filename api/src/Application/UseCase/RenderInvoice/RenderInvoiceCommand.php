<?php

namespace App\Application\UseCase\RenderInvoice;

class RenderInvoiceCommand
{
    public function __construct(
        public readonly int $accountId,
        public readonly string $invoiceNumber,
    ) {
    }
}
