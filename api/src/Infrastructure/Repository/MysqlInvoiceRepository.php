<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entities\Business;
use App\Domain\Entities\Invoice;
use App\Domain\Exception\InvoiceNotFoundException;
use App\Domain\Repository\InvoiceRepositoryInterface;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\InvoiceNumber;
use PDO;

class MysqlInvoiceRepository implements InvoiceRepositoryInterface
{
    public function __construct(private readonly PDO $PDO)
    {
    }

    public function getLastEmittedByBusiness(Business $business): ?Invoice
    {
        $stmt = $this->PDO->prepare(
            'SELECT * FROM invoice ' .
            'WHERE emitter_id = :emitter_id ' .
            'ORDER BY number DESC'
        );
        $emitterId = $business->id->getInt();
        $stmt->bindParam(':emitter_id', $emitterId);

        $stmt->execute();
        $result = $stmt->fetch();

        if (false === $result) {
            return null;
        }

        return $this->buildInvoice($result);
    }

    private function buildInvoice(mixed $result): Invoice
    {
        return new Invoice(
            new Id($result['id']),
            new InvoiceNumber($result['number']),
            new Id($result['emitter_id']),
            new Id($result['receiver_id']),
            new \DateTime($result['date']),
        );
    }
}
