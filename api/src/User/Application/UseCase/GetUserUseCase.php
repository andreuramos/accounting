<?php

namespace App\User\Application\UseCase;

use App\User\Domain\ValueObject\Email;

class GetUserUseCase
{
    public function __invoke(Email $userQuery): array
    {
        return [];
    }
}
