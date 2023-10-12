<?php

namespace App\Domain;

interface BusinessRepositoryInterface
{
    public function getByTaxNumber(string $taxNumber): ?Business;
    public function save(Business $business): void;
    public function getByUserIdOrFail(Id $userId): Business;
}
