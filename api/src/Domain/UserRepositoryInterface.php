<?php

namespace App\Domain;

use App\Domain\ValueObject\Email;

interface UserRepositoryInterface
{
    public function getByEmail(Email $email): ?User;
    public function getByEmailOrFail(Email $email): User;
    public function save(User $user): void;
    public function linkBusinessToUser(Id $userId, string $taxNumber): void;
}
