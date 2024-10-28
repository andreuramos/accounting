<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entities\Business;
use App\Domain\Entities\Invoice;
use App\Domain\Entities\InvoiceAggregate;
use App\Domain\Exception\InvoiceNotFoundException;
use App\Domain\Repository\InvoiceAggregateRepositoryInterface;
use App\Domain\ValueObject\Address;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\InvoiceLine;
use App\Domain\ValueObject\InvoiceNumber;
use App\Domain\ValueObject\Money;
use App\Domain\ValueObject\Percentage;

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

    public function findByBusinessIdAndNumber(Id $businessId, InvoiceNumber $invoiceNumber): InvoiceAggregate
    {
        throw new InvoiceNotFoundException((string)$invoiceNumber);
    }

    public function findByEmitterTaxNumberAndInvoiceNumber(
        string $emitterTaxId,
        InvoiceNumber $invoiceNumber
    ): ?InvoiceAggregate {
        $stmt = $this->pdo->prepare(
            'SELECT invoice.*, 
       emitter.name as emitter_name, 
       emitter.tax_name as emitter_tax_name,
       emitter.tax_id as emitter_tax_id,
       emitter.tax_address as emitter_tax_address,
       emitter.tax_zip_code as emitter_tax_zip_code,
       receiver.name as receiver_name,
       receiver.tax_name as receiver_tax_name,
       receiver.tax_id as receiver_tax_id,
       receiver.tax_address as receiver_tax_address,
       receiver.tax_zip_code as receiver_tax_zip_code
                FROM invoice ' .
            'LEFT JOIN business AS emitter ON emitter.id = invoice.emitter_id ' .
            'LEFT JOIN business AS receiver ON receiver.id = invoice.receiver_id ' .
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

        $invoice = $this->buildInvoice($result[0]);
        $invoiceLines = $this->getInvoiceLines($invoice);
        [$emitter, $receiver] = $this->buildBusinesses($result[0]);

        return new InvoiceAggregate($invoice, $invoiceLines, $emitter, $receiver);
    }

    public function findLastEmittedByBusiness(Business $business): ?Invoice
    {
        $stmt = $this->pdo->prepare(
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
        $query = 'INSERT INTO invoice_line ' .
            '(invoice_id, product, amount_cents, quantity, position, vat_percent) VALUES ';
        $single_line_placeholder = '(' . implode(',', array_fill(0, 6, '?')) . ')';
        $lines_placeholder = implode(',', array_fill(0, count($invoiceLines), $single_line_placeholder));
        $query .= $lines_placeholder;

        $stmt = $this->pdo->prepare($query);

        $data = [];
        /** @var InvoiceLine $line */
        foreach ($invoiceLines as $position => $line) {
            array_push(
                $data,
                $invoiceId->getInt(),
                $line->product,
                $line->amount->amountCents,
                $line->quantity,
                $position,
                $line->vat_percentage->value
            );
        }

        $stmt->execute($data);
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

    private function getInvoiceLines(Invoice $invoice): array
    {
        $lines = [];
        $stmt = $this->pdo->prepare("SELECT * FROM invoice_line WHERE invoice_id = :invoice_id;");
        $invoice_id = $invoice->id->getInt();
        $stmt->bindParam(':invoice_id', $invoice_id);
        $stmt->execute();
        $result = $stmt->fetchAll();

        foreach ($result as $line) {
            $lines[] = new InvoiceLine(
                $line['product'],
                1,
                new Money($line['amount_cents']),
                new Percentage($line['vat_percent']),
            );
        }

        return $lines;
    }

    private function buildBusinesses(mixed $result): array
    {
        $emitter = new Business(
            new Id($result['emitter_id']),
            $result['emitter_name'],
            $result['emitter_tax_name'],
            $result['emitter_tax_id'],
            new Address($result['emitter_tax_address'], $result['emitter_tax_zip_code']),
        );
        $receiver = new Business(
            new Id($result['receiver_id']),
            $result['receiver_name'],
            $result['receiver_tax_name'],
            $result['receiver_tax_id'],
            new Address($result['receiver_tax_address'], $result['receiver_tax_zip_code']),
        );
        return [$emitter, $receiver];
    }
}
