<?php

namespace App\User\Application\UseCase;

use App\User\Application\Command\RegisterUserCommand;
use App\User\Domain\Service\AccountCreator;
use App\User\Domain\Service\UserCreator;

class RegisterUserUseCase
{
    public function __construct(
        private readonly UserCreator $userCreator,
        private readonly AccountCreator $accountCreator,
    ) {
    }
    public function __invoke(RegisterUserCommand $command): void
    {
        ($this->userCreator)($command);
        ($this->accountCreator)($command->email());
    }
}
