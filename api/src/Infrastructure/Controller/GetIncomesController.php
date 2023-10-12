<?php

namespace App\Infrastructure\Controller;

use App\Application\UseCase\GetAccountIncomes\AccountIncomes;
use App\Application\UseCase\GetAccountIncomes\GetAccountIncomesCommand;
use App\Application\UseCase\GetAccountIncomes\GetAccountIncomesUseCase;
use App\Domain\Income;
use App\Domain\UserRepositoryInterface;
use App\Infrastructure\ApiResponse;
use App\Infrastructure\Auth\JWTDecoder;
use Symfony\Component\HttpFoundation\Request;

class GetIncomesController extends AuthorizedController
{
    public function __construct(
        JWTDecoder $tokenDecoder,
        UserRepositoryInterface $userRepository,
        private readonly GetAccountIncomesUseCase $getUserIncomesUseCase,
    ) {
        parent::__construct($tokenDecoder, $userRepository);
    }

    public function __invoke(Request $request): ApiResponse
    {
        $this->auth($request);

        $command = new GetAccountIncomesCommand($this->authUser->accountId());
        $accountIncomes = ($this->getUserIncomesUseCase)($command);

        return new ApiResponse($this->exposableArray($accountIncomes));
    }

    private function exposableArray(AccountIncomes $accountIncomes): array
    {
        return array_map(function (Income $income) {
            return [
                'id' => $income->id->getInt(),
                'account_id' => $income->accountId->getInt(),
                'amount_cents' => $income->amount->amountCents,
                'currency' => $income->amount->currency,
                'description' => $income->description,
                'date' => $income->date->format('Y-m-d'),
            ];
        }, $accountIncomes->incomes);
    }
}
