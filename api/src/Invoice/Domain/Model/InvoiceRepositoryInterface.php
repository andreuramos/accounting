<?php

namespace App\Invoice\Domain\Model;

use App\Invoice\Domain\Entity\Invoice;
use App\Tax\Domain\Entity\Business;

interface InvoiceRepositoryInterface
{
    public function save(Invoice $invoice);
    public function getLastEmittedByBusiness(Business $business): ?Invoice;
}
