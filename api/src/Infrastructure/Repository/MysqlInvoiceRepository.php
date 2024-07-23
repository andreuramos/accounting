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
            'INSERT INTO invoice (number, emitter_id, receiver_id, date) ' .
            'VALUES (:number, :emitter_id, :receiver_id, :date)'
        );

        $number = $invoice->invoiceNumber->number;
        $stmt->bindParam(':number', $number);
        $emitterId = $invoice->emitterBusinessId->getInt();
        $stmt->bindParam(':emitter_id', $emitterId);
        $receiverId = $invoice->receiverBusinessId->getInt();
        $stmt->bindParam(':receiver_id', $receiverId);
        $date = $invoice->dateTime->format('Y-m-d');
        $stmt->bindParam(':date', $date);

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

        return $this->buildInvoice($result);
    }

    public function findByBusinessIdAndNumber(Id $businessId, InvoiceNumber $invoiceNumber): Invoice
    {
        throw new InvoiceNotFoundException((string)$invoiceNumber);
    }

    public function findByEmitterTaxNumberAndInvoiceNumber(string $emitterTaxId, InvoiceNumber $invoiceNumber): ?Invoice
    {
        $stmt = $this->PDO->prepare(
            'SELECT invoice.* FROM invoice ' .
            'LEFT JOIN business emitter ON emitter.id = invoice.emitter_id ' .
            'WHERE invoice.number = :number AND emitter.tax_id = :emitter_tax_id;'
        );
        $number = $invoiceNumber->number;
        $stmt->bindParam(':number', $number);
        $stmt->bindParam(':emitter_tax_id', $emitterTaxId);

        $stmt->execute();

        $result = $stmt->fetchAll();
        if (!count($result)) {
            return null;
        }

        return $this->buildInvoice($result[0]);
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
