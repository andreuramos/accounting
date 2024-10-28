<?php

namespace App\Domain\Repository;

use App\Domain\Entities\Business;
use App\Domain\Entities\Invoice;
use App\Domain\Entities\InvoiceAggregate;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\InvoiceNumber;

interface InvoiceAggregateRepositoryInterface
{
    public function save(InvoiceAggregate $invoiceAggregate): Id;

    public function findByBusinessIdAndNumber(Id $businessId, InvoiceNumber $invoiceNumber): InvoiceAggregate;

    public function findByEmitterTaxNumberAndInvoiceNumber(
        string $emitterTaxId,
        InvoiceNumber $invoiceNumber
    ): ?InvoiceAggregate;
    public function findLastEmittedByBusiness(Business $business): ?Invoice;
}
