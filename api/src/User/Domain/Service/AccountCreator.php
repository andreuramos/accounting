<?php

namespace App\User\Domain\Service;

use App\Shared\Domain\ValueObject\Id;
use App\User\Domain\Entity\Account;
use App\User\Domain\Model\AccountRepositoryInterface;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;

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
