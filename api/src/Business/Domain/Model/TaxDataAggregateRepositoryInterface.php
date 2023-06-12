<?php

namespace App\Business\Domain\Model;

use App\Business\Domain\Entity\TaxData;

interface TaxDataAggregateRepositoryInterface
{
    public function save(TaxData $taxDataAggregate): void;
}
