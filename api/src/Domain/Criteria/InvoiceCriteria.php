<?php

namespace App\Domain\Criteria;

use App\Domain\ValueObject\Id;
use DateTime;

class InvoiceCriteria
{
    private ?Id $accountIdFilter = null;
    private ?string $emitterTaxNumberFilter = null;
    private ?string $receiverTaxNumberFilter = null;
    private ?DateTime $fromDateFilter = null;
    private ?DateTime $toDateFilter = null;

    public function filterByAccountId(Id $accountId): InvoiceCriteria
    {
        $this->accountIdFilter = $accountId;
        return $this;
    }

    public function filterByEmitterTaxNumber(string $emitterTaxNumber): InvoiceCriteria
    {
        $this->emitterTaxNumberFilter = $emitterTaxNumber;
        return $this;
    }

    public function filterByReceiverTaxNumber(string $receiverTaxNumber): InvoiceCriteria
    {
        $this->receiverTaxNumberFilter = $receiverTaxNumber;
        return $this;
    }

    public function filterByFromDate(DateTime $fromDate): InvoiceCriteria
    {
        $this->fromDateFilter = $fromDate;
        return $this;
    }

    public function filterByToDate(DateTime $toDate): InvoiceCriteria
    {
        $this->toDateFilter = $toDate;
        return $this;
    }

    public function emitterTaxNumber(): ?string
    {
        return $this->emitterTaxNumberFilter;
    }

    public function receiverTaxNumber(): ?string
    {
        return $this->receiverTaxNumberFilter;
    }

    public function fromDate(): ?DateTime
    {
        return $this->fromDateFilter;
    }

    public function toDate(): ?DateTime
    {
        return $this->toDateFilter;
    }
}
