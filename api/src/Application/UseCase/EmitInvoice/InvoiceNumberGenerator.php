<?php

namespace App\Application\UseCase\EmitInvoice;

use App\Application\Service\Timestamper;
use App\Domain\Entities\Business;
use App\Domain\Repository\InvoiceAggregateRepositoryInterface;
use App\Domain\ValueObject\InvoiceNumber;

class InvoiceNumberGenerator
{
    public function __construct(
        private readonly InvoiceAggregateRepositoryInterface $invoiceAggregateRepository,
        private readonly Timestamper $timestamper,
    ) {
    }

    public function __invoke(Business $business): InvoiceNumber
    {
        $timestamp = ($this->timestamper)();
        $yearSuffix = $timestamp->format('Y');

        $correlativeNumber = $this->getNextInvoiceNumber($business, (int) $yearSuffix);
        $eightDigits = sprintf("%05d", $correlativeNumber);

        return new InvoiceNumber($yearSuffix . '-' . $eightDigits);
    }

    private function getNextInvoiceNumber(
        Business $business,
        int $currentYear,
    ): int {
        $lastInvoice = $this->invoiceAggregateRepository->findLastEmittedByBusiness($business);

        if (null === $lastInvoice || !$lastInvoice->wasEmittedInYear($currentYear)) {
            return 1;
        }

        $correlativeValue = (int) substr($lastInvoice->invoiceNumber->number, 5);
        return $correlativeValue + 1;
    }
}
