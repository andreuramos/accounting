<?php

namespace App\User\Application\UseCase;

use App\User\Application\Command\LoginCommand;
use App\User\Application\Result\LoginResult;

class LoginUseCase
{
    public function __invoke(LoginCommand $command): LoginResult
    {
        return new LoginResult("", "");
    }
}
