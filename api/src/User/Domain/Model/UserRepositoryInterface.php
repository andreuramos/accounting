<?php

namespace App\User\Domain\Model;

use App\User\Domain\Entity\User;

interface UserRepositoryInterface
{
    public function save(User $user): int;
}
