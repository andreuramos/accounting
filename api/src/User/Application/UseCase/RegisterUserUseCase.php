<?php

namespace App\User\Application\UseCase;

use App\User\Application\Command\RegisterUserCommand;
use App\User\Domain\Service\UserRegisterer;

class RegisterUserUseCase
{
    public function __construct(private readonly UserRegisterer $userRegisterer)
    {
    }
    public function __invoke(RegisterUserCommand $command)
    {
        $this->userRegisterer->execute($command);
    }
}
