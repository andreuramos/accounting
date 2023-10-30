<?php

namespace App\Domain\Repository;

use App\Domain\Entities\Business;
use App\Domain\ValueObject\Id;

interface BusinessRepositoryInterface
{
    public function getByTaxNumber(string $taxNumber): ?Business;
    public function save(Business $business): void;
    public function getByUserIdOrFail(Id $userId): Business;
}
