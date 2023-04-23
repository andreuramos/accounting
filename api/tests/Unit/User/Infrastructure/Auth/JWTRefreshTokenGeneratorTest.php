<?php

namespace Test\Unit\User\Infrastructure\Auth;

use App\Shared\Domain\ValueObject\Id;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\Email;
use App\User\Infrastructure\Auth\JWTRefreshTokenGenerator;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PHPUnit\Framework\TestCase;

class JWTRefreshTokenGeneratorTest extends TestCase
{
    public function test_token_can_be_decoded()
    {
        $user = new User(new Id(1), new Email("my@email.com"), "");
        $generator = new JWTRefreshTokenGenerator('key', 2592000);

        $refresh = $generator->__invoke($user);

        $decodedToken = (array) JWT::decode($refresh->value, new Key('key', 'HS256'));
        $this->assertArrayHasKey('email', $decodedToken);
        $this->assertEquals(date_create()->getTimestamp() + 2592000, $decodedToken['expiration']);
    }
}
