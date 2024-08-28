<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entities\Invoice;
use App\Domain\Entities\InvoiceAggregate;
use App\Domain\Repository\InvoiceAggregateRepositoryInterface;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\InvoiceLine;

class MysqlInvoiceAggregateRepository implements InvoiceAggregateRepositoryInterface
{
    public function __construct(
        private readonly \PDO $pdo
    ) {
    }

    public function save(InvoiceAggregate $invoiceAggregate): Id
    {
        $invoice = $invoiceAggregate->invoice();
        $invoiceId = $this->saveInvoice($invoice);

        $this->saveInvoiceLines($invoiceAggregate->invoiceLines(), $invoiceId);

        return $invoiceId;
    }

    private function saveInvoice(Invoice $invoice): Id
    {
        $stmt = $this->pdo->prepare(
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

        return new Id($this->pdo->lastInsertId());
    }

    private function saveInvoiceLines($invoiceLines, Id $invoiceId): void
    {
        $query = 'INSERT INTO invoice_line (invoice_id, product, amount_cents, quantity) VALUES ';
        $single_line_placeholder = '(' . implode(',', array_fill(0, 4, '?')) . ')';
        $lines_placeholder = implode(',', array_fill(0, count($invoiceLines), $single_line_placeholder));
        $query .= $lines_placeholder;

        $stmt = $this->pdo->prepare($query);

        $data = [];
        /** @var InvoiceLine $line */
        foreach ($invoiceLines as $line) {
            array_push(
                $data,
                $invoiceId->getInt(),
                $line->product,
                $line->amount->amountCents,
                $line->quantity,
            );
        }

        $stmt->execute($data);
    }
}
