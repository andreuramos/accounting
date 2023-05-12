<?php

namespace App\Tax\Infrastructure\Repository;

use App\Tax\Domain\Aggregate\TaxDataAggregate;
use App\Tax\Domain\Model\TaxDataAggregateRepositoryInterface;

class MysqlTaxDataAggregateRepository implements TaxDataAggregateRepositoryInterface
{
    public function __construct(
        private readonly \PDO $PDO
    ) {
    }

    public function save(TaxDataAggregate $taxDataAggregate): void
    {
        if (!$this->alreadyExists($taxDataAggregate)) {
            $stmt = $this->PDO->prepare(
                'INSERT INTO tax_data (user_id, tax_name, tax_number, address, zip_code) ' .
                'VALUES (:user_id, :tax_name, :tax_number, :address, :zip)'
            );
        } else {
            $stmt = $this->PDO->prepare(
                'UPDATE tax_data SET ' .
                'tax_name=":tax_name", tax_number=":tax_number", ' .
                'address=":address", zip_code=":zip" where user_id=:userId'
            );
        }

        $userId = $taxDataAggregate->userId->getInt();
        $stmt->bindParam(':user_id', $userId);
        $taxName = $taxDataAggregate->taxName;
        $stmt->bindParam(':tax_name', $taxName);
        $taxNumber = $taxDataAggregate->taxNumber;
        $stmt->bindParam(':tax_number', $taxNumber);
        $address = $taxDataAggregate->address->street;
        $stmt->bindParam(':address', $address);
        $zip = $taxDataAggregate->address->zip;
        $stmt->bindParam(':zip', $zip);

        $stmt->execute();
    }

    private function alreadyExists(TaxDataAggregate $taxDataAggregate): bool
    {
        $userId = $taxDataAggregate->userId->getInt();
        $query = $this->PDO->query("SELECT * FROM tax_data WHERE user_id = " . $userId . ";");
        $query->execute();

        return !empty($query->fetchAll());
    }
}
