<?php

namespace App\User\Application\UseCase;

use App\User\Application\Auth\AuthTokenDecoderInterface;
use App\User\Application\Command\RefreshTokensCommand;
use App\User\Application\Result\LoginResult;
use App\User\Domain\Exception\InvalidCredentialsException;

class RefreshTokensUseCase
{
    public function __construct(
        private readonly AuthTokenDecoderInterface $tokenDecoder
    ) {
    }

    public function __invoke(RefreshTokensCommand $command): LoginResult
    {
        throw new InvalidCredentialsException();
    }
}
