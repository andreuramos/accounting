<?php

namespace App\Domain;

interface TaxDataAggregateRepositoryInterface
{
    public function save(TaxData $taxDataAggregate): void;
}
