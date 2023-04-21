<?php

namespace App\User\Application\Auth;

use App\User\Domain\Entity\User;

interface RefreshTokenGeneratorInterface
{
    public function __invoke(User $user): AuthTokenInterface;
}
