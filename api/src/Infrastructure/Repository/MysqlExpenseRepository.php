<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entities\Expense;
use App\Domain\Repository\ExpenseRepositoryInterface;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Money;
use PDO;

class MysqlExpenseRepository implements ExpenseRepositoryInterface
{
    public function __construct(private readonly PDO $PDO)
    {
    }

    public function save(Expense $expense): void
    {
        $stmt = $this->PDO->prepare(
            "INSERT INTO expense (account_id, amount, description, date, invoice_id) " .
            "VALUES (:account_id, :amount, :description, :date, :invoice_id)"
        );

        $accountId = $expense->accountId->getInt();
        $amountCents = $expense->amount->amountCents;
        $description = $expense->description;
        $date = $expense->date->format('Y-m-d');
        $invoice_id = $expense->invoiceId?->getInt();

        $stmt->bindParam(':account_id', $accountId);
        $stmt->bindParam(':amount', $amountCents);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':invoice_id', $invoice_id);

        $stmt->execute();
    }

    public function getByAccountId(Id $accountId): array
    {
        $accountIdInt = $accountId->getInt();
        $stmt = $this->PDO->prepare(
            'SELECT * FROM expense WHERE account_id = :account_id'
        );
        $stmt->bindParam(':account_id', $accountIdInt);
        $stmt->execute();

        $results = [];
        foreach ($stmt->fetchAll() as $result) {
            $results[] = new Expense(
                new Id($result['id']),
                new Id($result['account_id']),
                new Money($result['amount']),
                $result['description'],
                new \DateTime($result['date']),
                $result['invoice_id'] ? new Id($result['invoice_id']) : null,
            );
        }

        return $results;
    }
}
