<?php

namespace App\Invoice\Infrastructure\Repository;

use App\Invoice\Domain\Entity\Invoice;
use App\Invoice\Domain\Model\InvoiceRepositoryInterface;

class MysqlInvoiceRepository implements InvoiceRepositoryInterface
{

    public function save(Invoice $invoice)
    {
    }
}
