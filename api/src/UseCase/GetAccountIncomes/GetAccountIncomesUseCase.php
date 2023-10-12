<?php

namespace App\UseCase\GetAccountIncomes;

use App\Domain\IncomeRepositoryInterface;

class GetAccountIncomesUseCase
{
    public function __construct(
        private readonly IncomeRepositoryInterface $incomeRepository
    ) {
    }

    public function __invoke(GetAccountIncomesCommand $command): AccountIncomes
    {
        $incomes = $this->incomeRepository->getByAccountId(
            $command->accountId
        );

        return new AccountIncomes($incomes);
    }
}
