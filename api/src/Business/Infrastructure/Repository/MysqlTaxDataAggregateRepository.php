<?php

namespace App\Business\Infrastructure\Repository;

use App\Business\Domain\Entity\TaxData;
use App\Business\Domain\Model\TaxDataAggregateRepositoryInterface;
use App\Shared\Domain\ValueObject\Id;

class MysqlTaxDataAggregateRepository implements TaxDataAggregateRepositoryInterface
{
    public function __construct(
        private readonly \PDO $PDO
    ) {
    }

    public function save(TaxData $taxDataAggregate): void
    {
        $taxDataId = $this->getTaxDataId($taxDataAggregate->userId);
        if (null === $taxDataId) {
            $this->createTaxData($taxDataAggregate);
        } else {
            $this->updateTaxData($taxDataAggregate, $taxDataId);
        }
    }

    private function getTaxDataId(Id $userId): ?int
    {
        $userIdInt = $userId->getInt() + 1;
        $query = $this->PDO->query("SELECT tax_data_id FROM user WHERE id = " . $userIdInt . ";");
        $query->execute();

        $taxDataId = $query->fetch();

        if (false === $taxDataId) {
            return null;
        }

        return $taxDataId[0];
    }

    private function createTaxData(TaxData $taxDataAggregate): void
    {
        $stmt = $this->PDO->prepare(
            'INSERT INTO tax_data (tax_name, tax_number, address, zip_code) ' .
            'VALUES (:tax_name, :tax_number, :address, :zip_code)'
        );

        $taxName = $taxDataAggregate->taxName;
        $stmt->bindParam(':tax_name', $taxName);
        $taxNumber = $taxDataAggregate->taxNumber;
        $stmt->bindParam(':tax_number', $taxNumber);
        $address = $taxDataAggregate->address->street;
        $stmt->bindParam(':address', $address);
        $zip = $taxDataAggregate->address->zip;
        $stmt->bindParam(':zip_code', $zip);
        $stmt->execute();

        $insertedId = $this->PDO->lastInsertId();

        $userId = $taxDataAggregate->userId->getInt();
        $this->PDO->query(
            'UPDATE user SET tax_data_id = ' . $insertedId .
            ' WHERE id = ' . $userId . ';'
        );
    }

    private function updateTaxData(TaxData $taxDataAggregate, int $taxDataId): void
    {
        $stmt = $this->PDO->prepare(
            'UPDATE tax_data SET ' .
            'tax_name=:tax_name, tax_number=:tax_number, ' .
            'address=:address, zip_code=:zip_code where id=:id'
        );

        $stmt->bindParam(':id', $taxDataId);
        $taxName = $taxDataAggregate->taxName;
        $stmt->bindParam(':tax_name', $taxName);
        $taxNumber = $taxDataAggregate->taxNumber;
        $stmt->bindParam(':tax_number', $taxNumber);
        $address = $taxDataAggregate->address->street;
        $stmt->bindParam(':address', $address);
        $zip = $taxDataAggregate->address->zip;
        $stmt->bindParam(':zip_code', $zip);

        $stmt->execute();
    }
}
