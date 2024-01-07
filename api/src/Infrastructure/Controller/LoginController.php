<?php

namespace App\Infrastructure\Controller;

use App\Application\UseCase\Login\LoginCommand;
use App\Application\UseCase\Login\LoginUseCase;
use App\Domain\Exception\MissingMandatoryParameterException;
use App\Domain\ValueObject\Email;
use App\Infrastructure\ApiResponse;
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

        return new ApiResponse(
            [
            "token" => (string) $result->token,
            "refresh" => (string) $result->refresh,
            ]
        );
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
