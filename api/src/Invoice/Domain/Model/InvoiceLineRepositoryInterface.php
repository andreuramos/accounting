<?php

namespace App\Invoice\Domain\Model;

use App\Invoice\Domain\Entity\InvoiceLine;
use App\Invoice\Domain\ValueObject\InvoiceNumber;

interface InvoiceLineRepositoryInterface
{
    public function addToInvoice(InvoiceNumber $invoiceNumber, InvoiceLine $line): void;
}
