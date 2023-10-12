<?php

namespace App\Domain\Repository;

use App\Domain\Entities\Business;
use App\Domain\Entities\Invoice;
use App\Domain\ValueObject\Id;

interface InvoiceRepositoryInterface
{
    public function save(Invoice $invoice): Id;
    public function getLastEmittedByBusiness(Business $business): ?Invoice;
}
