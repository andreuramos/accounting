<?php

namespace Test\Unit\User\Application\UseCase;

use App\Shared\Domain\ValueObject\Id;
use App\User\Application\Auth\AuthTokenDecoderInterface;
use App\User\Application\Command\RefreshTokensCommand;
use App\User\Application\UseCase\RefreshTokensUseCase;
use App\User\Domain\Entity\User;
use App\User\Domain\Exception\InvalidAuthToken;
use App\User\Domain\Exception\InvalidCredentialsException;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Domain\ValueObject\AuthToken;
use App\User\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class RefreshTokensUseCaseTest extends TestCase
{
    use ProphecyTrait;

    private $tokenDecoder;
    private $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->tokenDecoder = $this->prophesize(AuthTokenDecoderInterface::class);
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);
    }

    public function test_invalid_token_throws_exception()
    {
        $command = new RefreshTokensCommand("im.really.invalid");
        $this->tokenDecoder->__invoke("im.really.invalid")->willThrow(InvalidAuthToken::class);
        $useCase = $this->getUseCase();

        $this->expectException(InvalidCredentialsException::class);

        $useCase->__invoke($command);
    }

    public function test_not_found_user_throws_exception()
    {
        $command = new RefreshTokensCommand("jwt.wrong.payload");
        $this->tokenDecoder->__invoke("jwt.wrong.payload")->willReturn([
            'user' => "weird@email.com",
            'expiration' => 123
        ]);
        $this->userRepository->getByEmail(new Email('weird@email.com'))
            ->willReturn(null);
        $useCase = $this->getUseCase();

        $this->expectException(InvalidCredentialsException::class);

        $useCase->__invoke($command);
    }

    public function test_invalidated_token_throws_exception()
    {
        $command = new RefreshTokensCommand("jwt.invalidated.refresh");
        $this->tokenDecoder->__invoke("jwt.invalidated.refresh")->willReturn([
            'email' => "existing@email.com",
            'expiration' => 123
        ]);
        $user = new User(
            new Id(1),
            new Email("existing@email.com"),
            ""
        );
        $user->setRefreshToken(new AuthToken("not.the.samelol"));
        $this->userRepository->getByEmail(new Email("existing@email.com"))
            ->willReturn($user);
        $useCase = $this->getUseCase();

        $this->expectException(InvalidCredentialsException::class);

        $useCase->__invoke($command);
    }

    private function getUseCase(): RefreshTokensUseCase
    {
        return new RefreshTokensUseCase(
            $this->tokenDecoder->reveal(),
            $this->userRepository->reveal()
        );
    }
}
