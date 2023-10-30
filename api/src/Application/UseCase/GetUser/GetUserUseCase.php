<?php

namespace App\Application\UseCase\GetUser;

use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;

class GetUserUseCase
{
    public function __construct(
        private readonly UserRepositoryInterface $userRepository
    ) {
    }

    public function __invoke(Email $email): array
    {
        $user = $this->userRepository->getByEmailOrFail($email);
        return $user->toExposableArray();
    }
}
