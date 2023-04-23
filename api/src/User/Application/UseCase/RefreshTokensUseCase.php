<?php

namespace App\User\Application\UseCase;

use App\User\Application\Auth\AuthTokenDecoderInterface;
use App\User\Application\Command\RefreshTokensCommand;
use App\User\Application\Result\LoginResult;
use App\User\Domain\Entity\User;
use App\User\Domain\Exception\InvalidAuthToken;
use App\User\Domain\Exception\InvalidCredentialsException;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;

class RefreshTokensUseCase
{
    public function __construct(
        private readonly AuthTokenDecoderInterface $tokenDecoder,
        private readonly UserRepositoryInterface $userRepository
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

        return new LoginResult("", "");
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
