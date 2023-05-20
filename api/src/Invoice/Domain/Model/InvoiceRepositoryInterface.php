<?php

namespace App\Invoice\Domain\Model;

use App\Invoice\Domain\Entity\Invoice;

interface InvoiceRepositoryInterface
{
    public function save(Invoice $invoice);
}
