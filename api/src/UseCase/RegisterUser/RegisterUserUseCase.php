<?php

namespace App\UseCase\RegisterUser;

use App\Domain\AccountRepositoryInterface;
use App\Domain\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Service\AccountCreator;
use App\Service\UserCreator;

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
        $account = $this->accountRepository->getByOwnerEmailOrFail($email);

        $user->setAccountId($account->id);
        $this->userRepository->save($user);
    }
}
