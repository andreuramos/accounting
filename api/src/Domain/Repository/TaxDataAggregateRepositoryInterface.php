<?php

namespace App\Domain\Repository;

use App\Domain\ValueObject\TaxData;

interface TaxDataAggregateRepositoryInterface
{
    public function save(TaxData $taxDataAggregate): void;
}
