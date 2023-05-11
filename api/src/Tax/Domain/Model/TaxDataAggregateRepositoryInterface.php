<?php

namespace App\Tax\Domain\Model;

use App\Tax\Domain\Aggregate\TaxDataAggregate;

interface TaxDataAggregateRepositoryInterface
{
    public function save(TaxDataAggregate $taxDataAggregate): void;
}
