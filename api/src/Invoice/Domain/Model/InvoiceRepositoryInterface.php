<?php

namespace App\Invoice\Domain\Model;

use App\Business\Domain\Entity\Business;
use App\Invoice\Domain\Entity\Invoice;
use App\Shared\Domain\ValueObject\Id;

interface InvoiceRepositoryInterface
{
    public function save(Invoice $invoice): Id;
    public function getLastEmittedByBusiness(Business $business): ?Invoice;
}
