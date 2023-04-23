<?php

namespace App\User\Application\Auth;

use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\AuthToken;

interface AuthTokenGeneratorInterface
{
    public function __invoke(User $user): AuthToken;
}
