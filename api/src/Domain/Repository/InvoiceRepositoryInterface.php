<?php

namespace App\Domain\Repository;

use App\Domain\Entities\Business;
use App\Domain\Entities\Invoice;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\InvoiceNumber;

interface InvoiceRepositoryInterface
{
    public function getLastEmittedByBusiness(Business $business): ?Invoice;
}
