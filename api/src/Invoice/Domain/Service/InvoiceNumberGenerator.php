<?php

namespace App\Invoice\Domain\Service;

use App\Invoice\Domain\Entity\Business;
use App\Invoice\Domain\Model\InvoiceRepositoryInterface;
use App\Invoice\Domain\ValueObject\InvoiceNumber;
use App\Shared\Application\Service\Timestamper;

class InvoiceNumberGenerator
{
    public function __construct(
        private readonly InvoiceRepositoryInterface $invoiceRepository,
        private readonly Timestamper $timestamper,
    ) {
    }

    public function __invoke(Business $business): InvoiceNumber
    {
        $timestamp = ($this->timestamper)();
        $yearSuffix = $timestamp->format('Y');

        $correlativeNumber = $this->getLastInvoiceNumber($business);
        $eightDigits = sprintf("%08d", $correlativeNumber);

        return new InvoiceNumber($yearSuffix.$eightDigits);
    }

    private function getLastInvoiceNumber(Business $business): int
    {
        return 1;
    }
}
