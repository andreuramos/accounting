<?php

namespace App\Domain\Repository;

use App\Domain\Entities\InvoiceLine;

interface InvoiceLineRepositoryInterface
{
    public function save(InvoiceLine $line): void;
}
