<?php

namespace App\Domain\Criteria;

use App\Domain\ValueObject\Id;

class InvoiceCriteria
{
    private ?Id $accountIdFilter = null;
    
    public function filterByAccountId(Id $accountId): InvoiceCriteria
    {
        $this->accountIdFilter = $accountId;
        return $this;
    }
}