<?php

namespace App\Application\Auth;

interface AuthTokenDecoderInterface
{
    public function __invoke(string $token): array;
}
