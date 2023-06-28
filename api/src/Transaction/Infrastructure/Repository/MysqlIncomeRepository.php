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

    public function save(Income $income): Id
    {
        $stmt = $this->pdo->prepare(
            'INSERT INTO income ' .
            '(account_id, amount, description, date) ' .
            'VALUES (:account_id, :amount, :description, :date)'
        );

        $userId = $income->userId->getInt();
        $accountId = $income->accountId->getInt();
        $amountCents = $income->amount->amountCents;
        $description = $income->description;
        $date = $income->date->format('Y-m-d');

        $stmt->bindParam(':account_id', $accountId);
        $stmt->bindParam(':amount', $amountCents);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':date', $date);

        $stmt->execute();

        $lastInsertId = $this->pdo->lastInsertId();
        return new Id($lastInsertId);
    }

    public function getByAccountId(Id $accountId): array
    {
        $stmt = $this->pdo->prepare(
            'SELECT * FROM income WHERE account_id = :account_id'
        );

        $accountIdInt = $accountId->getInt();
        $stmt->bindParam(':account_id', $accountIdInt);
        $stmt->execute();

        $results = [];
        foreach ($stmt->fetchAll() as $dbIncome) {
            $results[] = $this->buildIncome($dbIncome);
        }

        return $results;
    }

    public function getByIdOrFail(Id $id): Income
    {
        $incomeId = $id->getInt();

        $stmt = $this->pdo->prepare(
            'SELECT * FROM income WHERE id=:id'
        );
        $stmt->bindParam(':id', $incomeId);
        $stmt->execute();

        $result = $stmt->fetch();

        return $this->buildIncome($result);
    }

    private function buildIncome(array $dbIncome): Income
    {
        return new Income(
            new Id($dbIncome['id']),
            new Id(null),
            new Id($dbIncome['account_id']),
            new Money($dbIncome['amount']),
            $dbIncome['description'],
            new \DateTime($dbIncome['date'])
        );
    }
}
