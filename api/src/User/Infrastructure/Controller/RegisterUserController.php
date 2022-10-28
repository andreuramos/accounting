<?php

namespace App\User\Infrastructure\Controller;

use App\User\Application\Command\RegisterUserCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;

class RegisterUserController
{
    public function __construct(private readonly MessageBusInterface $bus)
    {
    }

    public function __invoke(Request $request): Response
    {
        $requestContent = $this->getRequestBody($request);
        $this->guardRequestParams($requestContent);

        $email = $requestContent['email'];
        $command = new RegisterUserCommand($email);

        $this->bus->dispatch($command);

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
