<?php

namespace App\Domain\Criteria;

use App\Domain\ValueObject\Id;

class InvoiceCriteria
{
    private ?Id $accountIdFilter = null;
    private ?string $emitterTaxNumberFilter = null;
    private ?string $receiverTaxNumberFilter = null;
    
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
}