<?php

namespace App\Invoice\Infrastructure\Repository;

use App\Invoice\Domain\Entity\Business;
use App\Invoice\Domain\Model\BusinessRepositoryInterface;
use App\Shared\Domain\ValueObject\Id;
use App\Tax\Domain\Entity\TaxData;
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
            'SELECT b.id as business_id, b.name, ' .
            ' td.id as tax_data_id, td.* FROM business b LEFT JOIN tax_data td ' .
            'ON b.taxDataId = td.id ' .
            'WHERE td.tax_number = :tax_number'
        );
        $stmt->bindParam(':tax_number', $taxNumber);
        $stmt->execute();

        $result = $stmt->fetch();
        if (false === $result) {
            return null;
        }

        $taxData = new TaxData(
            new Id($result['tax_data_id']),
            $result['tax_name'],
            $result['tax_number'],
            new Address($result['address'], $result['zip_code'])
        );
        $business = new Business(
            new Id($result['business_id']),
            $result['name'],
            $taxData
        );

        return $business;
    }

    public function save(Business $business): void
    {
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
