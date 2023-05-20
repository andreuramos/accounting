<?php

namespace App\Invoice\Infrastructure\Repository;

use App\Invoice\Domain\Entity\Business;
use App\Invoice\Domain\Model\BusinessRepositoryInterface;
use App\Shared\Domain\ValueObject\Id;

class MysqlBusinessRepository implements BusinessRepositoryInterface
{

    public function getByTaxNumber(string $taxNumber): ?Business
    {
        return null;
    }

    public function save(Business $business): Id
    {
        return new Id(null);
    }
}
