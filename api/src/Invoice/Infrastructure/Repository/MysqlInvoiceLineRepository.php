<?php

namespace App\Invoice\Infrastructure\Repository;

use App\Invoice\Domain\Entity\InvoiceLine;
use App\Invoice\Domain\Model\InvoiceLineRepositoryInterface;
use App\Invoice\Domain\ValueObject\InvoiceNumber;

class MysqlInvoiceLineRepository implements InvoiceLineRepositoryInterface
{
    public function addToInvoice(InvoiceNumber $invoiceNumber, InvoiceLine $line): void
    {
    }
}
