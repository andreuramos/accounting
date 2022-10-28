<?php

namespace App\User\Infrastructure\Controller;

use App\User\Application\Command\RegisterUserCommand;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;

class RegisterUserController
{
    public function __construct(private readonly MessageBusInterface $bus)
    {
    }

    public function __invoke(Request $request): Response
    {
        $request_content = json_decode(
            $request->getContent(),
            true,
            512,
            JSON_THROW_ON_ERROR
        );
        $email = $request_content['email'];
        $command = new RegisterUserCommand($email);

        $this->bus->dispatch($command);

        return new Response("OK", 200);
    }
}
