<?php

namespace App\Application\Service;

use App\Application\UseCase\RegisterUser\RegisterUserCommand;
use App\Domain\Entities\User;
use App\Domain\Exception\UserAlreadyExistsException;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;

class UserCreator
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository,
        private readonly Hasher $hasher
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
