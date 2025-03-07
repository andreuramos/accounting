<?php

namespace App\Domain\Events;

use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\InvoiceNumber;

class InvoiceEmittedEvent extends Event
{
    public function __construct(
        private readonly Id $accountId,
        private \DateTime $timestamp,
        private InvoiceNumber $invoiceNumber,
    ) {
        parent::__construct("InvoiceEmitted", $timestamp);
    }

    public function jsonSerialize(): array
    {
        return [
            'name' => $this->name,
            'accountId' => (string) $this->accountId,
            'timestamp' => $this->timestamp->format('Y-m-d H:i:s'),
            'invoiceNumber' => (string) $this->invoiceNumber,
        ];
    }
}