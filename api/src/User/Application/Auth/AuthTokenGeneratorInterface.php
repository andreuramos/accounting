<?php

namespace App\User\Application\Auth;

use App\User\Domain\Entity\User;

interface AuthTokenGeneratorInterface
{
    public function __invoke(User $user): AuthTokenInterface;
}
