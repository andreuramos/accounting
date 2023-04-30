<?php

namespace App\Transaction\Infrastructure\Repository;

use App\Transaction\Domain\Entity\Expense;
use App\Transaction\Domain\Model\ExpenseRepositoryInterface;
use PDO;

class MysqlExpenseRepository implements ExpenseRepositoryInterface
{
    public function __construct(private readonly PDO $PDO)
    {
    }

    public function save(Expense $expense): void
    {
        $stmt = $this->PDO->prepare(
            "INSERT INTO expense (user_id, amount, description, date) " .
            "VALUES (:user_id, :amount, :description, :date)"
        );

        $userId = $expense->userId->getInt();
        $amountCents = $expense->amount->amountCents;
        $description = $expense->description;
        $date = $expense->date->format('Y-m-d');

        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':amount', $amountCents);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':date', $date);

        $stmt->execute();
    }
}
