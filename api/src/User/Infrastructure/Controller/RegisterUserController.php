<?php

namespace App\User\Infrastructure\Controller;

use App\Shared\Domain\Exception\MissingMandatoryParameterException;
use App\User\Application\Command\RegisterUserCommand;
use App\User\Application\UseCase\RegisterUserUseCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class RegisterUserController
{
    public function __construct(private readonly RegisterUserUseCase $registerUserUseCase)
    {
    }

    public function __invoke(Request $request): Response
    {
        $requestContent = $this->getRequestBody($request);
        $this->guardRequestParams($requestContent);

        $email = $requestContent['email'];
        $password = $requestContent['password'];
        $command = new RegisterUserCommand($email, $password);

        ($this->registerUserUseCase)($command);

        return new Response("OK", 200);
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
