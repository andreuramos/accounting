<?php

namespace App\Domain\Repository;

use App\Domain\Entities\User;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;

interface UserRepositoryInterface
{
    public function getByEmail(Email $email): ?User;
    public function getByEmailOrFail(Email $email): User;
    public function save(User $user): void;
    public function linkBusinessToUser(Id $userId, string $taxNumber): void;
}
