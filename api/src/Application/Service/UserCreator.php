<?php

namespace App\Application\Service;

use App\Application\UseCase\RegisterUser\RegisterUserCommand;
use App\Domain\Exception\UserAlreadyExistsException;
use App\Domain\Id;
use App\Domain\User;
use App\Domain\UserRepositoryInterface;
use App\Domain\ValueObject\Email;

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
