<?php

namespace App\Transaction\Infrastructure\Controller;

use App\Shared\Domain\Exception\MissingMandatoryParameterException;
use App\Shared\Infrastructure\ApiResponse;
use App\Shared\Infrastructure\Controller\AuthorizedController;
use App\Transaction\Application\Command\CreateIncomeCommand;
use App\Transaction\Application\UseCase\CreateIncomeUseCase;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Infrastructure\Auth\JWTDecoder;
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
