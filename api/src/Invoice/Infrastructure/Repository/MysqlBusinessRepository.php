<?php

namespace App\Invoice\Infrastructure\Repository;

use App\Invoice\Domain\Entity\Business;
use App\Invoice\Domain\Model\BusinessRepositoryInterface;
use App\Shared\Domain\ValueObject\Id;
use App\Tax\Domain\Entity\TaxData;
use App\Tax\Domain\ValueObject\Address;

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

    public function getByUserIdOrFail(Id $userId): Business
    {
        // @TODO: implement this
        return new Business(
            new Id(1),
            "",
            new TaxData(
                new Id(null),
                '.',
                '',
                new Address('', '')
            )
        );
    }
}
