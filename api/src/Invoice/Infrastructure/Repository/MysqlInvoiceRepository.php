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
        $emitterId = $invoice->emitter->id->getInt();
        $stmt->bindParam(':emitter_id', $emitterId);
        $receiverId = $invoice->receiver->id->getInt();
        $stmt->bindParam(':receiver_id', $receiverId);
        $date = $invoice->dateTime->format('Y-m-d');
        $stmt->bindParam(':date', $date);
        $incomeId = $invoice->income->id->getInt();
        $stmt->bindParam(':income_id', $incomeId);

        $stmt->execute();
    }

    public function getLastEmittedByBusiness(Business $business): ?Invoice
    {
        $stmt = $this->PDO->prepare(
            'SELECT INV.*, ' .
                'EMITTER.id as emitter_id, ' .
                'EMITTER.name as emitter_name, ' .
                'EMITTER.taxDataId as emitter_tax_data_id, ' .
                'EMITTERTAX.tax_name as emitter_tax_name, ' .
                'EMITTERTAX.tax_number as emitter_tax_number, ' .
                'EMITTERTAX.address as emitter_tax_address, ' .
                'EMITTERTAX.zip_code as emitter_tax_zip_code ' .
            'FROM invoice INV ' .
                'LEFT JOIN business EMITTER ON INV.emitter_id = EMITTER.id ' .
                'LEFT JOIN tax_data EMITTERTAX on EMITTER.taxDataId = EMITTERTAX.id ' .
                'LEFT JOIN business RECEIVER ON INV.receiver_id = RECEIVER.id ' .
                'LEFT JOIN tax_data RECEIVERTAX on RECEIVER.taxDataId = RECEIVERTAX.id ' .
                'LEFT JOIN income INC ON INC.id = INV.income_id ' .
            'WHERE INV.emitter_id = :emitter_id ' .
            'ORDER BY INV.number DESC'
        );
        $emitterId = $business->id->getInt();
        $stmt->bindParam(':emitter_id', $emitterId);

        $stmt->execute();
        $result = $stmt->fetch();

        if (false === $result) {
            return null;
        }

        $emitter = new Business(
            new Id($result['emitter_id']),
            $result['emitter_name'],
            new TaxData(
                new Id($result['emitter_tax_data_id']),
                $result['emitter_tax_name'],
                $result['emitter_tax_number'],
                new Address(
                    $result['emitter_tax_address'],
                    $result['emitter_tax_zip_code']
                )
            )
        );
        $receiver = new Business(
            new Id(null),
            "whatever, this is gonna be refactored",
            new TaxData(
                new Id(null), "whatever", "whatever",
                new Address("street", "zip")
            )
        );

        return new Invoice(
            new Id($result['id']),
            new InvoiceNumber($result['number']),
            new Income(
                new Id(null),
                new Id(null),
                new Money(1, "EUR"),
                "whatever",
                new \DateTime()
            ),
            $emitter,
            $receiver,
            new \DateTime()
        );
    }
}
