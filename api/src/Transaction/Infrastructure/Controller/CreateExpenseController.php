<?php

namespace App\Transaction\Infrastructure\Controller;

use App\Shared\Domain\Exception\MissingMandatoryParameterException;
use App\Shared\Infrastructure\ApiResponse;
use App\Shared\Infrastructure\Controller\AuthorizedController;
use App\Transaction\Application\Command\CreateExpenseCommand;
use App\Transaction\Application\UseCase\CreateExpenseUseCase;
use App\User\Application\Auth\AuthTokenDecoderInterface;
use App\User\Domain\Model\UserRepositoryInterface;
use Symfony\Component\HttpFoundation\Request;

class CreateExpenseController extends AuthorizedController
{
    public function __construct(
        AuthTokenDecoderInterface $tokenDecoder,
        UserRepositoryInterface $userRepository,
        private readonly CreateExpenseUseCase $createExpenseUseCase
    ) {
        parent::__construct($tokenDecoder, $userRepository);
    }

    private const MANDATORY_PARAMETERS = [
        'amount', 'description', 'date'
    ];

    public function __invoke(Request $request): ApiResponse
    {
        $this->auth($request);
        $requestContent = json_decode($request->getContent(), true, 512, JSON_THROW_ON_ERROR);
        $this->guardMandatoryParameters($requestContent);

        $command = new CreateExpenseCommand(
            (int) $requestContent['amount'],
            $requestContent['description'],
            $requestContent['date']
        );
        ($this->createExpenseUseCase)($command);

        return new ApiResponse([]);
    }

    private function guardMandatoryParameters(array $request)
    {
        foreach (self::MANDATORY_PARAMETERS as $parameter) {
            if (!isset($request[$parameter])) {
                throw new MissingMandatoryParameterException($parameter);
            }
        }
    }
}
