<?php

namespace App\Domain\Repository;

use App\Domain\Entities\InvoiceAggregate;
use App\Domain\ValueObject\Id;

interface InvoiceAggregateRepositoryInterface
{
    public function save(InvoiceAggregate $invoiceAggregate): Id;
}
