<?php

namespace Test\Unit\Infrastructure\Auth;

use App\Domain\Entities\User;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;
use App\Infrastructure\Auth\JWTRefreshTokenGenerator;
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
