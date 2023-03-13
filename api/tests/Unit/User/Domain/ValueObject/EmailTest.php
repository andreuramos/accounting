<?php

namespace Test\Unit\User\Domain\ValueObject;

use App\User\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    public function test_isntantiates_valid_email()
    {
        $email = new Email('andreu@email.com');

        $this->assertInstanceOf(Email::class, $email);
    }
}
