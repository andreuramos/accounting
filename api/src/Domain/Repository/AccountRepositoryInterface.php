<?php

namespace App\Domain\Repository;

use App\Domain\Entities\Account;
use App\Domain\ValueObject\Email;

interface AccountRepositoryInterface
{
    public function save(Account $account): void;
    public function getByOwnerEmailOrFail(Email $email): Account;
}
