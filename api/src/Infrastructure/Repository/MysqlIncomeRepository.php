<?php

namespace App\Infrastructure\Repository;

use App\Domain\Entities\Income;
use App\Domain\Repository\IncomeRepositoryInterface;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Money;
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
            '(account_id, amount, description, date, invoice_id) ' .
            'VALUES (:account_id, :amount, :description, :date, :invoice_id)'
        );

        $accountId = $income->accountId->getInt();
        $amountCents = $income->amount->amountCents;
        $description = $income->description;
        $date = $income->date->format('Y-m-d');
        $invoice_id = $income->invoiceId?->getInt();

        $stmt->bindParam(':account_id', $accountId);
        $stmt->bindParam(':amount', $amountCents);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':date', $date);
        $stmt->bindParam(':invoice_id', $invoice_id);

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
            new Id($dbIncome['account_id']),
            new Money($dbIncome['amount']),
            $dbIncome['description'],
            new \DateTime($dbIncome['date'])
        );
    }
}
