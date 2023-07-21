<?php

namespace App\Invoice\Domain\Model;

use App\Invoice\Domain\Entity\InvoiceLine;

interface InvoiceLineRepositoryInterface
{
    public function save(InvoiceLine $line): void;
}
