<?php

namespace App\Invoice\Infrastructure\Repository;

use App\Invoice\Domain\Entity\Invoice;
use App\Invoice\Domain\Model\InvoiceRepositoryInterface;

class MysqlInvoiceRepository implements InvoiceRepositoryInterface
{
    public function __construct(private readonly \PDO $PDO)
    {
    }

    public function save(Invoice $invoice): void
    {
        $stmt = $this->PDO->prepare(
            'INSERT INTO invoice (number, emitter_id, receiver_id, date) ' .
            'VALUES (:number, :emitter_id, :receiver_id, :date)'
        );

        $number = $invoice->invoiceNumber->number;
        $stmt->bindParam(':number', $number);
        $emitterId = $invoice->emitter->id->getInt();
        $stmt->bindParam(':emitter_id', $emitterId);
        $receiverId = $invoice->receiver->id->getInt();
        $stmt->bindParam(':receiver_id', $receiverId);
        $date = $invoice->dateTime->format('Y-m-d');
        $stmt->bindParam(':date', $date);

        $stmt->execute();
    }
}
