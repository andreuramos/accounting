<?php

namespace App\User\Infrastructure\Controller;

use App\Shared\Domain\Exception\MissingMandatoryParameterException;
use App\Shared\Infrastructure\ApiResponse;
use App\User\Application\Command\LoginCommand;
use App\User\Application\UseCase\LoginUseCase;
use App\User\Domain\ValueObject\Email;
use Symfony\Component\HttpFoundation\Request;

class LoginController
{
    public function __construct(private readonly LoginUseCase $loginUseCase)
    {
    }

    public function __invoke(Request $request): ApiResponse
    {
        $requestContent = $this->getRequestBody($request);
        $this->guardRequiredParams($requestContent);

        $command = new LoginCommand(
            new Email($requestContent['email']),
            $requestContent['password']
        );

        $result = ($this->loginUseCase)($command);

        return new ApiResponse([
            "token" => (string) $result->token,
            "refresh" => (string) $result->refresh,
        ]);
    }

    private function getRequestBody(Request $request): array
    {
        return json_decode(
            $request->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
    }

    private function guardRequiredParams(array $requestContent)
    {
        if (!array_key_exists('email', $requestContent)) {
            throw new MissingMandatoryParameterException("email");
        }
        if (!array_key_exists('password', $requestContent)) {
            throw new MissingMandatoryParameterException("email");
        }
    }
}
