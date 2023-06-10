<?php

namespace App\User\Application\UseCase;

use App\Shared\Domain\ValueObject\Id;
use App\User\Application\Command\RegisterUserCommand;
use App\User\Domain\Model\AccountRepositoryInterface;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Domain\Service\AccountCreator;
use App\User\Domain\Service\UserCreator;
use App\User\Domain\ValueObject\Email;

class RegisterUserUseCase
{
    public function __construct(
        private readonly UserCreator $userCreator,
        private readonly AccountCreator $accountCreator,
        private readonly UserRepositoryInterface $userRepository,
        private readonly AccountRepositoryInterface $accountRepository,
    ) {
    }
    public function __invoke(RegisterUserCommand $command): void
    {
        ($this->userCreator)($command);
        ($this->accountCreator)($command->email());
        $this->assignUserItsOwnAccount(new Email($command->email()));
    }

    private function assignUserItsOwnAccount(Email $email): void
    {
        $user = $this->userRepository->getByEmailOrFail($email);
        $account = $this->accountRepository->getByOwnerEmail($email);

        $user->setAccountId($account->id);
        $this->userRepository->save($user);
    }
}
