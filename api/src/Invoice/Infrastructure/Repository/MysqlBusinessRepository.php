<?php

namespace App\Invoice\Infrastructure\Repository;

use App\Invoice\Domain\Entity\Business;
use App\Invoice\Domain\Exception\BusinessNotFoundException;
use App\Invoice\Domain\Model\BusinessRepositoryInterface;
use App\Shared\Domain\ValueObject\Id;
use App\Tax\Domain\ValueObject\Address;
use PDO;

class MysqlBusinessRepository implements BusinessRepositoryInterface
{
    public function __construct(private readonly PDO $PDO)
    {
    }

    public function getByTaxNumber(string $taxNumber): ?Business
    {
        $stmt = $this->PDO->prepare(
            'SELECT * FROM business ' .
            'WHERE tax_id = :tax_number'
        );
        $stmt->bindParam(':tax_number', $taxNumber);
        $stmt->execute();

        $result = $stmt->fetch();
        if (false === $result) {
            return null;
        }

        $business = new Business(
            new Id($result['id']),
            $result['name'],
            $result['tax_name'],
            $result['tax_id'],
            new Address($result['tax_address'], $result['tax_zip_code'])
        );

        return $business;
    }

    public function save(Business $business): void
    {
        $stmt = $this->PDO->prepare(
            'INSERT INTO business ' .
            '(name, tax_name, tax_id, tax_address, tax_zip_code) ' .
            'VALUES (:name, :tax_name, :tax_number, :address, :zip_code)'
        );
        $businessName = $business->name;
        $stmt->bindParam(':name', $businessName);
        $taxName = $business->taxName;
        $stmt->bindParam(':tax_name', $taxName);
        $taxNumber = $business->taxNumber;
        $stmt->bindParam(':tax_number', $taxNumber);
        $address = $business->taxAddress->street;
        $stmt->bindParam(':address', $address);
        $zip = $business->taxAddress->zip;
        $stmt->bindParam(':zip_code', $zip);

        $stmt->execute();
    }

    public function getByUserIdOrFail(Id $userId): Business
    {
        $stmt = $this->PDO->prepare(
            'SELECT b.id as business_id, b.* ' .
            'FROM business b ' .
            'LEFT JOIN user u ON u.tax_data_id = b.id ' .
            'WHERE u.id = :user_id'
        );
        $id = $userId->getInt();
        $stmt->bindParam(':user_id', $id);
        $stmt->execute();

        $result = $stmt->fetch();
        if (false === $result) {
            throw new BusinessNotFoundException();
        }

        return new Business(
            new Id($result['id']),
            $result['name'],
            $result['tax_name'],
            $result['tax_id'],
            new Address($result['tax_address'], $result['tax_zip_code'])
        );
    }
}
