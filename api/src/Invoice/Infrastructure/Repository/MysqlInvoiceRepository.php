<?php

namespace App\Invoice\Infrastructure\Repository;

use App\Invoice\Domain\Entity\Business;
use App\Invoice\Domain\Entity\Invoice;
use App\Invoice\Domain\Model\InvoiceRepositoryInterface;
use App\Invoice\Domain\ValueObject\InvoiceNumber;
use App\Shared\Domain\ValueObject\Id;
use App\Tax\Domain\Entity\TaxData;
use App\Tax\Domain\ValueObject\Address;
use App\Transaction\Domain\Entity\Income;
use App\Transaction\Domain\ValueObject\Money;
use PDO;

class MysqlInvoiceRepository implements InvoiceRepositoryInterface
{
    public function __construct(private readonly PDO $PDO)
    {
    }

    public function save(Invoice $invoice): void
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
}
