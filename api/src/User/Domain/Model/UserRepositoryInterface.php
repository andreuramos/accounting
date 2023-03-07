<?php

namespace App\User\Domain\Model;

use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\Email;

interface UserRepositoryInterface
{
    public function getByEmail(Email $email): ?User;
    public function save(User $user): int;
}
