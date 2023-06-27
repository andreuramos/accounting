<?php

namespace App\Transaction\Infrastructure\Controller;

use App\Shared\Infrastructure\ApiResponse;
use App\Shared\Infrastructure\Controller\AuthorizedController;
use App\Transaction\Application\Command\GetAccountExpensesCommand;
use App\Transaction\Application\UseCase\GetAccountExpensesUseCase;
use App\Transaction\Domain\Entity\Expense;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Infrastructure\Auth\JWTDecoder;
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

        return new ApiResponse($accountExpenses->toArray());
    }
}
