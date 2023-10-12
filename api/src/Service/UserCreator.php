<?php

namespace App\Service;

use App\Domain\Exception\UserAlreadyExistsException;
use App\Domain\Id;
use App\Domain\User;
use App\Domain\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\UseCase\RegisterUser\RegisterUserCommand;

class UserCreator
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly HasherInterface $hasher
    ) {
    }

    public function __invoke(RegisterUserCommand $command): void
    {
        $email = new Email($command->email());
        if (null !== $this->userRepository->getByEmail($email)) {
            throw new UserAlreadyExistsException();
        }
        $hashedPassword = $this->hasher->hash($command->password());
        $user = new User(
            new Id(null),
            $email,
            $hashedPassword
        );

        $this->userRepository->save($user);
    }
}
