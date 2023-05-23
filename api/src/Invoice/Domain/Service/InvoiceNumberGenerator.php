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
        return new InvoiceNumber($timestamp->format('Y'));
    }
}
