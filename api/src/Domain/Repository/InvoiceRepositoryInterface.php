<?php

namespace App\Domain\Repository;

use App\Domain\Entities\Business;
use App\Domain\Entities\Invoice;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\InvoiceNumber;

interface InvoiceRepositoryInterface
{
    public function save(Invoice $invoice): Id;
    public function getLastEmittedByBusiness(Business $business): ?Invoice;
    public function findByBusinessIdAndNumber(Id $businessId, InvoiceNumber $invoiceNumber): Invoice;
    public function findByEmitterTaxNumberAndInvoiceNumber(string $emitterTaxId, InvoiceNumber $invoiceNumber);
}
