<?php

namespace App\Domain\Service;

use App\Domain\Entities\InvoiceAggregate;

interface InvoiceRendererInterface
{
    public function __invoke(InvoiceAggregate $invoiceAggregate): string;
}