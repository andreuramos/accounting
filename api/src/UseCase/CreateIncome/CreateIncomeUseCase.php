<?php

namespace App\UseCase\CreateIncome;

use App\Domain\Id;
use App\Domain\Income;
use App\Domain\IncomeRepositoryInterface;
use App\Domain\Money;

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
