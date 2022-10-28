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
        $command = new RegisterUserCommand();

        $this->bus->dispatch($command);

        return new Response("OK", 200);
    }
}
