<?php

namespace App\User\Domain\Model;

use App\User\Domain\Entity\Account;
use App\User\Domain\ValueObject\Email;

interface AccountRepositoryInterface
{
    public function save(Account $account): void;
    public function getByOwnerEmailOrFail(Email $email): Account;
}
