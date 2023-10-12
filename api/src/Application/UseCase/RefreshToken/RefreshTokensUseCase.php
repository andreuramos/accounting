<?php

namespace App\Application\UseCase\RefreshToken;

use App\Application\Auth\AuthTokenDecoderInterface;
use App\Application\Auth\AuthTokenGeneratorInterface;
use App\Application\UseCase\Login\LoginResult;
use App\Domain\Exception\InvalidAuthToken;
use App\Domain\Exception\InvalidCredentialsException;
use App\Domain\User;
use App\Domain\UserRepositoryInterface;
use App\Domain\ValueObject\Email;

class RefreshTokensUseCase
{
    public function __construct(
        private readonly AuthTokenDecoderInterface $tokenDecoder,
        private readonly UserRepositoryInterface $userRepository,
        private readonly AuthTokenGeneratorInterface $authTokenGenerator,
        private readonly RefreshTokenGeneratorInterface $refreshTokenGenerator
    ) {
    }

    public function __invoke(RefreshTokensCommand $command): LoginResult
    {
        try {
            $tokenPayload = ($this->tokenDecoder)($command->refreshToken);
            if (!isset($tokenPayload['email']) || !isset($tokenPayload['expiration'])) {
                throw new InvalidAuthToken();
            }
        } catch (InvalidAuthToken $exception) {
            throw new InvalidCredentialsException();
        }

        $user = $this->getUser($tokenPayload);
        $this->guardTokenNotInvalidated($user, $command->refreshToken);

        $authToken = ($this->authTokenGenerator)($user);
        $refreshToken = ($this->refreshTokenGenerator)($user);

        $user->setRefreshToken($refreshToken);
        $this->userRepository->save($user);

        return new LoginResult(
            $authToken,
            $refreshToken
        );
    }

    private function getUser(array $tokenPayload): User
    {
        $user = $this->userRepository->getByEmail(
            new Email($tokenPayload['email'])
        );
        if (null === $user) {
            throw new InvalidCredentialsException();
        }
        return $user;
    }

    private function guardTokenNotInvalidated(User $user, string $refreshToken): void
    {
        if ((string)$user->refreshToken() !== $refreshToken) {
            throw new InvalidCredentialsException();
        }
    }
}
