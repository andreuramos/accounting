<?php

namespace App\Infrastructure\Controller;

use App\Application\UseCase\RegisterUser\RegisterUserCommand;
use App\Application\UseCase\RegisterUser\RegisterUserUseCase;
use App\Domain\Exception\MissingMandatoryParameterException;
use App\Infrastructure\ApiResponse;
use Symfony\Component\HttpFoundation\Request;

class RegisterUserController
{
    public function __construct(private readonly RegisterUserUseCase $registerUserUseCase)
    {
    }

    public function __invoke(Request $request): ApiResponse
    {
        $requestContent = $this->getRequestBody($request);
        $this->guardRequestParams($requestContent);

        $email = $requestContent['email'];
        $password = $requestContent['password'];
        $command = new RegisterUserCommand($email, $password);

        ($this->registerUserUseCase)($command);

        return new ApiResponse(['status' => "OK"]);
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

    private function guardRequestParams(array $requestContent): void
    {
        if (!array_key_exists('email', $requestContent)) {
            throw new MissingMandatoryParameterException("email");
        }
        if (!array_key_exists('password', $requestContent)) {
            throw new MissingMandatoryParameterException("email");
        }
    }
}
