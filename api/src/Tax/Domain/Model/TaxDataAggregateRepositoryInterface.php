<?php

namespace App\Tax\Domain\Model;

use App\Tax\Domain\Entity\TaxData;

interface TaxDataAggregateRepositoryInterface
{
    public function save(TaxData $taxDataAggregate): void;
}
