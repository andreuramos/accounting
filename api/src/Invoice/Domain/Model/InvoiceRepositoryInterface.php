<?php

namespace App\Invoice\Domain\Model;

use App\Business\Domain\Entity\Business;
use App\Invoice\Domain\Entity\Invoice;

interface InvoiceRepositoryInterface
{
    public function save(Invoice $invoice);
    public function getLastEmittedByBusiness(Business $business): ?Invoice;
}
