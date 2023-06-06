<?php

namespace App\User\Application\UseCase;

use App\User\Application\Command\RegisterUserCommand;
use App\User\Domain\Model\AccountRepositoryInterface;
use App\User\Domain\Service\UserCreator;

class RegisterUserUseCase
{
    public function __construct(
        private readonly UserCreator $userCreator,
        private readonly AccountRepositoryInterface $accountRepository,
    ) {
    }
    public function __invoke(RegisterUserCommand $command): void
    {
        ($this->userCreator)($command);
        $this->accountRepository->createForUser($command->email());
    }
}
