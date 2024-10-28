<?php

namespace App\Domain\Criteria;

class InvoiceCriteria
{
    private $criteria = [];
    
    public function filterBy(string $field, mixed $value): InvoiceCriteria
    {
        $this->criteria[$field] = $value;
        return $this;
    }
}