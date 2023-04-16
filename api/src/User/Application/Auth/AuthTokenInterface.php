<?php

namespace App\User\Application\Auth;

interface AuthTokenInterface
{
    public function __toString(): string;
}
