<?php

namespace App\User\Application\Auth;

interface AuthTokenDecoderInterface
{
    public function __invoke(string $token): array;
}
