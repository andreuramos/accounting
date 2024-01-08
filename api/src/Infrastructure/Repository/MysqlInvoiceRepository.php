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

    public function save(Invoice $invoice): Id
    {
        $stmt = $this->PDO->prepare(
            'INSERT INTO invoice (number, emitter_id, receiver_id, date, income_id) ' .
            'VALUES (:number, :emitter_id, :receiver_id, :date, :income_id)'
        );

        $number = $invoice->invoiceNumber->number;
        $stmt->bindParam(':number', $number);
        $emitterId = $invoice->emitterBusinessId->getInt();
        $stmt->bindParam(':emitter_id', $emitterId);
        $receiverId = $invoice->receiverBusinessId->getInt();
        $stmt->bindParam(':receiver_id', $receiverId);
        $date = $invoice->dateTime->format('Y-m-d');
        $stmt->bindParam(':date', $date);
        $incomeId = $invoice->incomeId->getInt();
        $stmt->bindParam(':income_id', $incomeId);

        $stmt->execute();

        return new Id($this->PDO->lastInsertId());
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

        return new Invoice(
            new Id($result['id']),
            new InvoiceNumber($result['number']),
            new Id($result['income_id']),
            new Id($result['emitter_id']),
            new Id($result['receiver_id']),
            new \DateTime()
        );
    }

    public function findByBusinessIdAndNumber(Id $businessId, InvoiceNumber $invoiceNumber): Invoice
    {
        throw new InvoiceNotFoundException((string) $invoiceNumber);
    }

    public function findByEmitterTaxNumberAndInvoiceNumber(string $emitterTaxId, InvoiceNumber $invoiceNumber): ?Invoice
    {
        return null;
    }
}
