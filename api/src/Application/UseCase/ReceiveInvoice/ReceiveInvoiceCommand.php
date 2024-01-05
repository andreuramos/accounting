<?php

namespace App\Application\UseCase\ReceiveInvoice;

class ReceiveInvoiceCommand
{
    public function __construct(
        private readonly string $provider_name,
        private readonly string $provider_tax_name,
        private readonly string $provider_tax_number,
        private readonly string $provider_tax_address,
        private readonly string $provider_tax_zip_code,
        private readonly string $invoice_number,
        private readonly string $description,
        private readonly string $date,
        private readonly int $amount,
        private readonly int $taxes,
    ) {
    }
}