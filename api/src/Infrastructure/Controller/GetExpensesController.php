<?php

namespace App\Infrastructure\Controller;

use App\Domain\Expense;
use App\Domain\UserRepositoryInterface;
use App\Infrastructure\ApiResponse;
use App\Infrastructure\Auth\JWTDecoder;
use App\UseCase\GetAccountExpenses\AccountExpenses;
use App\UseCase\GetAccountExpenses\GetAccountExpensesCommand;
use App\UseCase\GetAccountExpenses\GetAccountExpensesUseCase;
use Symfony\Component\HttpFoundation\Request;

class GetExpensesController extends AuthorizedController
{
    public function __construct(
        JWTDecoder $tokenDecoder,
        UserRepositoryInterface $userRepository,
        private readonly GetAccountExpensesUseCase $getUserExpensesUseCase
    ) {
        parent::__construct($tokenDecoder, $userRepository);
    }

    public function __invoke(Request $request): ApiResponse
    {
        $this->auth($request);

        $command = new GetAccountExpensesCommand($this->authUser->accountId());
        $accountExpenses = ($this->getUserExpensesUseCase)($command);

        return new ApiResponse($this->exposableArray($accountExpenses));
    }

    private function exposableArray(AccountExpenses $accountExpenses): array
    {
        return array_map(function (Expense $expense) {
            return [
                'id' => $expense->id->getInt(),
                'account_id' => $expense->accountId->getInt(),
                'amount_cents' => $expense->amount->amountCents,
                'currency' => $expense->amount->currency,
                'description' => $expense->description,
                'date' => $expense->date->format('Y-m-d')
            ];
        }, $accountExpenses->expenses);
    }
}
