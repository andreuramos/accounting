<?php

namespace App\Transaction\Infrastructure\Repository;

use App\Shared\Domain\ValueObject\Id;
use App\Transaction\Domain\Entity\Income;
use App\Transaction\Domain\Model\IncomeRepositoryInterface;
use App\Transaction\Domain\ValueObject\Money;
use App\User\Domain\Entity\User;
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

    public function getByUser(User $user): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM income WHERE user_id = :user_id'
        );

        $userId = $user->id()->getInt();
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();

        $results = [];
        foreach ($stmt->fetchAll() as $dbIncome) {
            $results[] = new Income(
                new Id($dbIncome['id']),
                new Id($dbIncome['user_id']),
                new Money($dbIncome['amount']),
                $dbIncome['description'],
                new \DateTime($dbIncome['date'])
            );
        }

        return $results;
    }
}
