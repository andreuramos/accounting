<?php

namespace App\User\Application\UseCase;

use App\Shared\Application\Service\HasherInterface;
use App\User\Application\Auth\RefreshTokenGeneratorInterface;
use App\User\Application\Command\LoginCommand;
use App\User\Application\Result\LoginResult;
use App\User\Application\Auth\AuthTokenGeneratorInterface;
use App\User\Domain\Entity\User;
use App\User\Domain\Exception\InvalidCredentialsException;
use App\User\Domain\Model\UserRepositoryInterface;

class LoginUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly HasherInterface $hasher,
        private readonly AuthTokenGeneratorInterface $authTokenGenerator,
        private readonly RefreshTokenGeneratorInterface $refreshTokenGenerator
    ) {
    }

    public function __invoke(LoginCommand $command): LoginResult
    {
        $user = $this->userRepository->getByEmail($command->email);
        if (!$this->areCredentialsValid($user, $command->password)) {
            throw new InvalidCredentialsException();
        }

        $authToken = ($this->authTokenGenerator)($user);
        $refreshToken = ($this->refreshTokenGenerator)($user);

        $user->setRefreshToken($refreshToken);
        $this->userRepository->save($user);

        return new LoginResult($authToken, $refreshToken);
    }

    private function areCredentialsValid(?User $user, string $password): bool
    {
        if (null === $user) {
            return false;
        }

        return $this->hasher->hash($password) === $user->passwordHash();
    }
}
