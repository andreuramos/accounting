<?php

namespace App\Application\UseCase\ReceiveInvoice;

class ReceiveInvoiceCommand
{
    public function __construct(
        public readonly string $provider_name,
        public readonly string $provider_tax_name,
        public readonly string $provider_tax_number,
        public readonly string $provider_tax_address,
        public readonly string $provider_tax_zip_code,
        public readonly string $invoice_number,
        public readonly string $description,
        public readonly string $date,
        public readonly int $amount,
        public readonly int $taxes,
    ) {
    }
}