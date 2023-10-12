<?php

namespace App\UseCase\RenderInvoice;

class RenderInvoiceCommand
{
    public function __construct(
        public readonly int $accountId,
        public readonly string $invoiceNumber,
    ) {
    }
}
