<?php

namespace App\Infrastructure\Controller;

use App\Domain\MissingMandatoryParameterException;
use App\Domain\UserRepositoryInterface;
use App\Infrastructure\ApiResponse;
use App\Infrastructure\Auth\JWTDecoder;
use App\UseCase\CreateIncome\CreateIncomeCommand;
use App\UseCase\CreateIncome\CreateIncomeUseCase;
use Symfony\Component\HttpFoundation\Request;

class CreateIncomeController extends AuthorizedController
{
    private const MANDATORY_PARAMETERS = ['amount', 'description', 'date'];

    public function __construct(
        JWTDecoder $tokenDecoder,
        UserRepositoryInterface $userRepository,
        private readonly CreateIncomeUseCase $createIncomeUseCase,
    ) {
        parent::__construct($tokenDecoder, $userRepository);
    }

    public function __invoke(Request $request): ApiResponse
    {
        $this->auth($request);
        $requestContent = json_decode($request->getContent(), true);
        $this->guardMandatoryParameters($requestContent);

        $command = new CreateIncomeCommand(
            $this->authUser,
            $this->authUser->accountId(),
            $requestContent['amount'],
            $requestContent['description'],
            $requestContent['date'],
        );
        $createdId = ($this->createIncomeUseCase)($command);

        return new ApiResponse([
            'id' => $createdId->getInt()
        ]);
    }

    private function guardMandatoryParameters(array $request): void
    {
        foreach (self::MANDATORY_PARAMETERS as $parameter) {
            if (!isset($request[$parameter])) {
                throw new MissingMandatoryParameterException($parameter);
            }
        }
    }
}
