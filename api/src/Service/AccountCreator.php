<?php

namespace App\Service;

use App\Domain\Account;
use App\Domain\AccountRepositoryInterface;
use App\Domain\Id;
use App\Domain\UserRepositoryInterface;
use App\Domain\ValueObject\Email;

class AccountCreator
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly AccountRepositoryInterface $accountRepository,
    ) {
    }

    public function __invoke(string $userEmail): void
    {
        $email = new Email($userEmail);
        $user = $this->userRepository->getByEmailOrFail($email);

        $account = new Account(
            new Id(null),
            $user->id()
        );

        $this->accountRepository->save($account);
    }
}
