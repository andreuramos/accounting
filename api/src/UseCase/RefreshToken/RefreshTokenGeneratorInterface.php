<?php

namespace App\UseCase\RefreshToken;

use App\Domain\User;
use App\Domain\ValueObject\AuthToken;

interface RefreshTokenGeneratorInterface
{
    public function __invoke(User $user): AuthToken;
}
