<?php

namespace App\Transaction\Application\UseCase;

use App\Transaction\Application\Command\GetAccountIncomesCommand;
use App\Transaction\Application\Result\AccountIncomes;
use App\Transaction\Domain\Model\IncomeRepositoryInterface;

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
