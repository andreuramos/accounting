<?php

namespace App\User\Domain\Model;

interface AccountRepositoryInterface
{
    public function createForUser(string $email): void;
}
