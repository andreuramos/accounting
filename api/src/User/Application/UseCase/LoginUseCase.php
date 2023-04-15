<?php

namespace App\User\Application\UseCase;

use App\User\Application\Command\LoginCommand;
use App\User\Application\Result\LoginResult;
use App\User\Domain\Exception\InvalidCredentialsException;
use App\User\Domain\Model\UserRepositoryInterface;

class LoginUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function __invoke(LoginCommand $command): LoginResult
    {
        $user = $this->userRepository->getByEmail($command->email);
        if (null === $user) {
            throw new InvalidCredentialsException();
        }
        return new LoginResult("", "");
    }
}
