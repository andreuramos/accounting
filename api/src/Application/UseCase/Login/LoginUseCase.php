<?php

namespace App\Application\UseCase\Login;

use App\Application\Auth\AuthTokenGeneratorInterface;
use App\Application\Service\Hasher;
use App\Application\UseCase\RefreshToken\RefreshTokenGeneratorInterface;
use App\Domain\Entities\User;
use App\Domain\Exception\InvalidCredentialsException;
use App\Domain\Exception\UserNotFoundException;
use App\Domain\Repository\UserRepositoryInterface;

class LoginUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly Hasher $hasher,
        private readonly AuthTokenGeneratorInterface $authTokenGenerator,
        private readonly RefreshTokenGeneratorInterface $refreshTokenGenerator
    ) {
    }

    public function __invoke(LoginCommand $command): LoginResult
    {
        try {
            $user = $this->userRepository->getByEmailOrFail($command->email);
        } catch (UserNotFoundException $exception) {
            throw new InvalidCredentialsException();
        }
        if (!$this->areCredentialsValid($user, $command->password)) {
            throw new InvalidCredentialsException();
        }

        $authToken = ($this->authTokenGenerator)($user);
        $refreshToken = ($this->refreshTokenGenerator)($user);

        $user->setRefreshToken($refreshToken);
        $this->userRepository->save($user);

        return new LoginResult(
            $authToken,
            $refreshToken
        );
    }

    private function areCredentialsValid(User $user, string $password): bool
    {
        return $this->hasher->hash($password) === $user->passwordHash();
    }
}
