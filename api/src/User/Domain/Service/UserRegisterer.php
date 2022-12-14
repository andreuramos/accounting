<?php

namespace App\User\Domain\Service;

use App\User\Application\Command\RegisterUserCommand;
use App\User\Domain\Entity\User;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;

class UserRegisterer
{
    public function __construct(private readonly UserRepositoryInterface $userRepository)
    {
    }

    public function execute(RegisterUserCommand $command): void
    {
        $email = new Email($command->email());
        $user = new User($email);

        $this->userRepository->save($user);
    }
}
