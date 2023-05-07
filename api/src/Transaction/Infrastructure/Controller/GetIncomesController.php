<?php

namespace App\Transaction\Infrastructure\Controller;

use App\Shared\Infrastructure\ApiResponse;
use App\Shared\Infrastructure\Controller\AuthorizedController;
use App\Transaction\Application\Command\GetUserIncomesCommand;
use App\Transaction\Application\UseCase\GetUserIncomesUseCase;
use App\Transaction\Domain\Entity\Income;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Infrastructure\Auth\JWTDecoder;
use Symfony\Component\HttpFoundation\Request;

class GetIncomesController extends AuthorizedController
{
    public function __construct(
        JWTDecoder $tokenDecoder,
        UserRepositoryInterface $userRepository,
        private readonly GetUserIncomesUseCase $getUserIncomesUseCase,
    ) {
        parent::__construct($tokenDecoder, $userRepository);
    }

    public function __invoke(Request $request): ApiResponse
    {
        $this->auth($request);

        $command = new GetUserIncomesCommand($this->authUser);
        $userIncomes = ($this->getUserIncomesUseCase)($command);

        $response = array_map(function (Income $income) {
            return [
                'id' => $income->id->getInt(),
                'user_id' => $income->userId->getInt(),
                'amount_cents' => $income->amount->amountCents,
                'currency' => $income->amount->currency,
                'description' => $income->description,
                'date' => $income->date->format('Y-m-d'),
            ];
        }, $userIncomes->incomes);


        return new ApiResponse($response);
    }
}
