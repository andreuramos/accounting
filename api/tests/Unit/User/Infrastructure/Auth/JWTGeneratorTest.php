<?php

namespace Test\Unit\User\Infrastructure\Auth;

use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\Email;
use App\User\Infrastructure\Auth\JWTGenerator;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use PHPUnit\Framework\TestCase;

class JWTGeneratorTest extends TestCase
{
    private string $signatureKey = "myK3y";
    private int $ttl = 3600;

    public function test_token_can_be_decoded()
    {
        $user = new User(
            new Email("email@address.com"),
            'someHash'
        );
        $generator = new JWTGenerator($this->signatureKey, $this->ttl);

        $token = $generator($user);

        $decoded_payload = (array) JWT::decode($token, new Key($this->signatureKey, 'HS256'));
        $this->assertArrayHasKey('user', $decoded_payload);
        $this->assertEquals("email@address.com", $decoded_payload['user']);
    }
}
