<?php

namespace Test\Unit\User\Infrastructure\Auth;

use App\User\Domain\Exception\InvalidAuthToken;
use App\User\Infrastructure\Auth\JWTDecoder;
use Firebase\JWT\JWT;
use PHPUnit\Framework\TestCase;

class JWTDecoderTest extends TestCase
{
    public function test_decodes_a_valid_jwt()
    {
        $originalPayload = ["attr" => "value"];
        $token = JWT::encode($originalPayload, 'myKey', 'HS256');
        $service = new JWTDecoder('myKey');

        $decoded = $service($token);

        $this->assertEquals($originalPayload, $decoded);
    }

    public function test_throws_exception_when_invalid_token()
    {
        $service = new JWTDecoder('myKey');

        $this->expectException(InvalidAuthToken::class);

        $service("not.even.jwt");
    }
}
