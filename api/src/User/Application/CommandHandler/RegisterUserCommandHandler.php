<?php

namespace App\User\Application\CommandHandler;

use App\User\Application\Command\RegisterUserCommand;

class RegisterUserCommandHandler
{
    public function __invoke(RegisterUserCommand $command): void
    {
        dd(self::class);
    }
}
