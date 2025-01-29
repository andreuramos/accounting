<?php

namespace App\Domain\Repository;

use App\Domain\Criteria\InvoiceCriteria;
use App\Domain\Entities\Business;
use App\Domain\Entities\Invoice;
use App\Domain\Entities\InvoiceAggregate;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\InvoiceNumber;

interface InvoiceAggregateRepositoryInterface
{
    public function save(InvoiceAggregate $invoiceAggregate): Id;

    public function findByAccountIdAndNumber(Id $accountId, InvoiceNumber $invoiceNumber): InvoiceAggregate;

    public function findByEmitterTaxNumberAndInvoiceNumber(
        string $emitterTaxId,
        InvoiceNumber $invoiceNumber
    ): ?InvoiceAggregate;

    public function findLastEmittedByBusiness(Business $business): ?Invoice;

    public function getByCriteria(InvoiceCriteria $criteria): array;
}
