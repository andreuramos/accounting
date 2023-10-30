<?php

namespace Test\Unit\Domain\ValueObject;

use App\Domain\Exception\InvalidEmailException;
use App\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase
{
    const EMAIL_REGEX = "/^[\w+]+@([\w-]+\.)+[\w-]{2,4}$/";

    public function test_isntantiates_valid_email()
    {
        $email = new Email('andreu@email.com');

        $this->assertInstanceOf(Email::class, $email);
    }

    public function test_no_at_sign_email_cannot_be_intantiated()
    {
        $this->expectException(InvalidEmailException::class);

        new Email('EmailWithNoAtSign.com');
    }

    public function test_no_dot_email_cannot_be_instantiated()
    {
        $this->expectException(InvalidEmailException::class);

        new Email('emailWith@noDot');
    }

    public function test_special_character_email_cannot_be_instantiated()
    {
        $this->expectException(InvalidEmailException::class);

        new Email('strange!@email.com');
    }
}
