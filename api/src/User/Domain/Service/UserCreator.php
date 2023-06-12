<?php

namespace App\User\Domain\Service;

use App\Shared\Application\Service\HasherInterface;
use App\Shared\Domain\ValueObject\Id;
use App\User\Application\Command\RegisterUserCommand;
use App\User\Domain\Entity\User;
use App\User\Domain\Exception\UserAlreadyExistsException;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;

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