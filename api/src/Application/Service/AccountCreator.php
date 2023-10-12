<?php

namespace App\Application\Service;

use App\Domain\Entities\Account;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;

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
