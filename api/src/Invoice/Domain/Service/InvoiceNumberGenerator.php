<?php

namespace App\Invoice\Domain\Service;

use App\Invoice\Domain\Entity\Business;
use App\Invoice\Domain\ValueObject\InvoiceNumber;

class InvoiceNumberGenerator
{
    public function __invoke(Business $business): InvoiceNumber
    {
        return new InvoiceNumber('');
    }
}
