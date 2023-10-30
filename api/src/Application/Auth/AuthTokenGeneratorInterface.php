<?php

namespace App\Application\Auth;

use App\Domain\Entities\User;
use App\Domain\ValueObject\AuthToken;

interface AuthTokenGeneratorInterface
{
    public function __invoke(User $user): AuthToken;
}
