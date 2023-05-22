<?php

namespace App\Invoice\Domain\Model;

use App\Invoice\Domain\Entity\Business;
use App\Shared\Domain\ValueObject\Id;

interface BusinessRepositoryInterface
{
    public function getByTaxNumber(string $taxNumber): ?Business;
    public function save(Business $business): void;
    public function getByUserIdOrFail(Id $userId): Business;
}
