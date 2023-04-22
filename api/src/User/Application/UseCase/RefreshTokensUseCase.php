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
            if (!isset($tokenPayload['user']) || !isset($tokenPayload['expiration'])) {
                throw new InvalidAuthToken();
            }
        } catch (InvalidAuthToken $exception) {
            throw new InvalidCredentialsException();
        }

        $user = $this->getUser($tokenPayload);

        throw new InvalidCredentialsException();
    }

    private function getUser(array $tokenPayload): User
    {
        $user = $this->userRepository->getByEmail(
            new Email($tokenPayload['user'])
        );
        if (null === $user) {
            throw new InvalidCredentialsException();
        }
        return $user;
    }
}
