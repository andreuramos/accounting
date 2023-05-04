<?php

namespace App\Transaction\Infrastructure\Repository;

use App\Transaction\Domain\Entity\Income;
use App\Transaction\Domain\Model\IncomeRepositoryInterface;
use PDO;

class MysqlIncomeRepository implements IncomeRepositoryInterface
{
    public function __construct(
        private readonly PDO $pdo
    ) {
    }

    public function save(Income $income): void
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO income ' .
            '(user_id, amount, description, date) ' .
            'VALUES (:user_id, :amount, :description, :date)'
        );

        $userId = $income->userId->getInt();
        $amountCents = $income->amount->amountCents;
        $description = $income->description;
        $date = $income->date->format('Y-m-d');

        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':amount', $amountCents);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':date', $date);

        $stmt->execute();
    }
}
