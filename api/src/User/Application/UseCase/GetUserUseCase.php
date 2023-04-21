<?php

namespace App\User\Application\UseCase;

use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;

class GetUserUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function __invoke(Email $email): array
    {
        $user = $this->userRepository->getByEmail($email);
        return $user->toExposableArray();
    }
}
