<?php

namespace Test\Unit\User\Application\UseCase;

use App\User\Application\Auth\AuthTokenDecoderInterface;
use App\User\Application\Command\RefreshTokensCommand;
use App\User\Application\UseCase\RefreshTokensUseCase;
use App\User\Domain\Exception\InvalidAuthToken;
use App\User\Domain\Exception\InvalidCredentialsException;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class RefreshTokensUseCaseTest extends TestCase
{
    use ProphecyTrait;

    private $tokenDecoder;

    public function setUp(): void
    {
        parent::setUp();
        $this->tokenDecoder = $this->prophesize(AuthTokenDecoderInterface::class);
    }

    public function test_invalid_token_throws_exception()
    {
        $command = new RefreshTokensCommand("im.really.invalid");
        $this->tokenDecoder->__invoke("im.really.invalid")->willThrow(InvalidAuthToken::class);
        $useCase = new RefreshTokensUseCase($this->tokenDecoder->reveal());

        $this->expectException(InvalidCredentialsException::class);

        $useCase->__invoke($command);
    }
}
