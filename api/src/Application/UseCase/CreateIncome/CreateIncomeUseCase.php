<?php

namespace App\Application\UseCase\CreateIncome;

use App\Domain\Entities\Income;
use App\Domain\Repository\IncomeRepositoryInterface;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Money;

class CreateIncomeUseCase
{
    public function __construct(
        private readonly IncomeRepositoryInterface $incomeRepository
    ) {
    }

    public function __invoke(CreateIncomeCommand $command): Id
    {
        $income = new Income(
            new Id(null),
            $command->accountId,
            new Money($command->amountCents),
            $command->description,
            new \DateTime($command->date),
        );

        return $this->incomeRepository->save($income);
    }
}
