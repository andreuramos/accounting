<?php

namespace App\Tax\Domain\Model;

use App\Shared\Domain\ValueObject\Id;
use App\Tax\Domain\Entity\Business;

interface BusinessRepositoryInterface
{
    public function getByTaxNumber(string $taxNumber): ?Business;
    public function save(Business $business): void;
    public function getByUserIdOrFail(Id $userId): Business;
}
