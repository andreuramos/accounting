<?php

namespace App\Application\UseCase\RefreshToken;

use App\Domain\Entities\User;
use App\Domain\ValueObject\AuthToken;

interface RefreshTokenGeneratorInterface
{
    public function __invoke(User $user): AuthToken;
}
