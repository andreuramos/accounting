<?php

namespace App\Infrastructure\Repository;

use App\Domain\Expense;
use App\Domain\ExpenseRepositoryInterface;
use App\Domain\Id;
use App\Domain\Money;
use PDO;

class MysqlExpenseRepository implements ExpenseRepositoryInterface
{
    public function __construct(private readonly PDO $PDO)
    {
    }

    public function save(Expense $expense): void
    {
        $stmt = $this->PDO->prepare(
            "INSERT INTO expense (account_id, amount, description, date) " .
            "VALUES (:account_id, :amount, :description, :date)"
        );

        $accountId = $expense->accountId->getInt();
        $amountCents = $expense->amount->amountCents;
        $description = $expense->description;
        $date = $expense->date->format('Y-m-d');

        $stmt->bindParam(':account_id', $accountId);
        $stmt->bindParam(':amount', $amountCents);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':date', $date);

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
                new \DateTime($result['date'])
            );
        }

        return $results;
    }
}
