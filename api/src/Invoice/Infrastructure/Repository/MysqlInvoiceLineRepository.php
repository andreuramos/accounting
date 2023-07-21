<?php

namespace App\Invoice\Infrastructure\Repository;

use App\Invoice\Domain\Entity\InvoiceLine;
use App\Invoice\Domain\Model\InvoiceLineRepositoryInterface;

class MysqlInvoiceLineRepository implements InvoiceLineRepositoryInterface
{
    public function __construct(private readonly \PDO $PDO)
    {
    }

    public function save(InvoiceLine $line): void
    {
        $stmt = $this->PDO->prepare(
            'INSERT INTO invoice_line (invoice_id, product, amount_cents, quantity) VALUES' .
            '(:invoice_id, :product, :amount_cents, :quantity)'
        );

        $invoice_id = (string) $line->invoiceId;
        $stmt->bindParam(':invoice_id', $invoice_id);
        $product = $line->product;
        $stmt->bindParam(':product', $product);
        $amount_cents = $line->amount->amountCents;
        $stmt->bindParam(':amount_cents', $amount_cents);
        $quantity = $line->quantity;
        $stmt->bindParam(':quantity', $quantity);

        $stmt->execute();
    }
}
