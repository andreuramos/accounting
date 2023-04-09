<?php

namespace App\User\Infrastructure\Controller;

use App\User\Application\Command\RegisterUserCommand;
use App\User\Domain\Service\UserRegisterer;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

class RegisterUserController
{
    public function __construct(private readonly UserRegisterer $userRegisterer)
    {
    }

    public function __invoke(Request $request): Response
    {
        $requestContent = $this->getRequestBody($request);
        $this->guardRequestParams($requestContent);

        $email = $requestContent['email'];
        $password = $requestContent['password'];
        $command = new RegisterUserCommand($email, $password);

        $this->userRegisterer->execute($command);

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
            throw new MissingMandatoryParametersException("email");
        }
    }
}
