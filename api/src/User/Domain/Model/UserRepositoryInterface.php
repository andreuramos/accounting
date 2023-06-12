<?php

namespace App\User\Domain\Model;

use App\Shared\Domain\ValueObject\Id;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\Email;

interface UserRepositoryInterface
{
    public function getByEmail(Email $email): ?User;
    public function getByEmailOrFail(Email $email): User;
    public function save(User $user): void;
    public function linkBusinessToUser(Id $userId, string $taxNumber): void;
}
