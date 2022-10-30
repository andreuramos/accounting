<?php

namespace App\User\Domain\Entity;

use App\User\Domain\ValueObject\Email;

class User
{
    public function __construct(private readonly Email $email)
    {
    }

    public function email(): Email
    {
        return $this->email;
    }
}
