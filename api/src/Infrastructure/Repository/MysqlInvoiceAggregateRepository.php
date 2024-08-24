<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entities\InvoiceAggregate;
use App\Domain\Repository\InvoiceAggregateRepositoryInterface;
use App\Domain\ValueObject\Id;

class MysqlInvoiceAggregateRepository implements InvoiceAggregateRepositoryInterface
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    public function save(InvoiceAggregate $invoiceAggregate): Id
    {
        return new Id(null);
    }
}
