<?php

namespace Test\Unit\User\Application\UseCase;

use App\Shared\Domain\ValueObject\Id;
use App\User\Application\Auth\AuthTokenDecoderInterface;
use App\User\Application\Auth\AuthTokenGeneratorInterface;
use App\User\Application\Auth\RefreshTokenGeneratorInterface;
use App\User\Application\Command\RefreshTokensCommand;
use App\User\Application\Result\LoginResult;
use App\User\Application\UseCase\RefreshTokensUseCase;
use App\User\Domain\Entity\User;
use App\User\Domain\Exception\InvalidAuthToken;
use App\User\Domain\Exception\InvalidCredentialsException;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Domain\ValueObject\AuthToken;
use App\User\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class RefreshTokensUseCaseTest extends TestCase
{
    use ProphecyTrait;

    private $tokenDecoder;
    private $userRepository;
    private $authTokenGenerator;
    private $refreshTokenGenerator;

    public function setUp(): void
    {
        parent::setUp();
        $this->tokenDecoder = $this->prophesize(AuthTokenDecoderInterface::class);
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);
        $this->authTokenGenerator = $this->prophesize(AuthTokenGeneratorInterface::class);
        $this->refreshTokenGenerator = $this->prophesize(RefreshTokenGeneratorInterface::class);
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

    public function test_valid_token_generates_auth_n_refresh()
    {
        $command = new RefreshTokensCommand("jwt.valid.refresh");
        $this->tokenDecoder->__invoke("jwt.valid.refresh")->willReturn([
            'email' => "existing@email.com",
            'expiration' => 123
        ]);
        $user = new User(
            new Id(1),
            new Email("existing@email.com"),
            ""
        );
        $user->setRefreshToken(new AuthToken("jwt.valid.refresh"));
        $this->userRepository->getByEmail(new Email("existing@email.com"))
            ->willReturn($user);
        $authToken = new AuthToken("an.auth.token");
        $this->authTokenGenerator->__invoke($user)
            ->shouldBeCalled()
            ->willReturn($authToken);
        $refreshToken = new AuthToken('a.refresh.token');
        $this->refreshTokenGenerator->__invoke($user)
            ->shouldBeCalled()
            ->willReturn($refreshToken);
        $this->userRepository->save(Argument::type(User::class));
        $useCase = $this->getUseCase();

        $result = $useCase->__invoke($command);

        $this->assertInstanceOf(LoginResult::class, $result);
        $this->assertEquals($authToken, $result->token);
        $this->assertEquals($refreshToken, $result->refresh);
    }

    public function test_valid_token_updates_user()
    {
        $command = new RefreshTokensCommand("jwt.valid.refresh");
        $this->tokenDecoder->__invoke("jwt.valid.refresh")->willReturn([
            'email' => "existing@email.com",
            'expiration' => 123
        ]);
        $user = new User(
            new Id(1),
            new Email("existing@email.com"),
            ""
        );
        $user->setRefreshToken(new AuthToken("jwt.valid.refresh"));
        $this->userRepository->getByEmail(new Email("existing@email.com"))
            ->willReturn($user);
        $authToken = new AuthToken("an.auth.token");
        $this->authTokenGenerator->__invoke($user)
            ->willReturn($authToken);
        $refreshToken = new AuthToken('a.refresh.token');
        $this->refreshTokenGenerator->__invoke($user)
            ->willReturn($refreshToken);
        $updatedUser = new User(
            new Id(1),
            new Email("existing@email.com"),
            ""
        );
        $updatedUser->setRefreshToken($refreshToken);
        $this->userRepository->save($updatedUser)->shouldBeCalled();
        $useCase = $this->getUseCase();

        $useCase->__invoke($command);
    }

    private function getUseCase(): RefreshTokensUseCase
    {
        return new RefreshTokensUseCase(
            $this->tokenDecoder->reveal(),
            $this->userRepository->reveal(),
            $this->authTokenGenerator->reveal(),
            $this->refreshTokenGenerator->reveal()
        );
    }
}