<?php

namespace Test\Unit\User\Application\UseCase;

use App\Application\Auth\AuthTokenGeneratorInterface;
use App\Application\Service\HasherInterface;
use App\Application\UseCase\Login\LoginCommand;
use App\Application\UseCase\Login\LoginResult;
use App\Application\UseCase\Login\LoginUseCase;
use App\Application\UseCase\RefreshToken\RefreshTokenGeneratorInterface;
use App\Domain\Entities\User;
use App\Domain\Exception\InvalidCredentialsException;
use App\Domain\Exception\UserNotFoundException;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\AuthToken;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class LoginUseCaseTest extends TestCase
{
    use ProphecyTrait;
    private $userRepository;
    private $hasher;
    private $authTokenGenerator;
    private $refreshTokenGenerator;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);
        $this->hasher = $this->prophesize(HasherInterface::class);
        $this->authTokenGenerator = $this->prophesize(AuthTokenGeneratorInterface::class);
        $this->refreshTokenGenerator = $this->prophesize(RefreshTokenGeneratorInterface::class);
    }

    public function test_throws_exception_if_user_not_found()
    {
        $email = new Email("not@existing.com");
        $command = new LoginCommand($email, "mypass");
        $this->userRepository->getByEmailOrFail($email)->willThrow(UserNotFoundException::class);

        $this->expectException(InvalidCredentialsException::class);

        $useCase = $this->getUseCase();
        $useCase($command);
    }

    public function test_throws_exception_if_hashes_dont_match()
    {
        $email = new Email("existing@email.com");
        $user = new User(
            new Id(1),
            $email,
            "passHash"
        );
        $command = new LoginCommand($email, "mypass");
        $this->userRepository->getByEmailOrFail($email)->willReturn($user);
        $this->hasher->hash("mypass")->willReturn("notMyHash");

        $this->expectException(InvalidCredentialsException::class);

        $useCase = $this->getUseCase();
        $useCase($command);
    }

    public function test_returns_token_when_credentials_are_valid()
    {
        $email = new Email("existing@email.com");
        $user = new User(
            new Id(1),
            $email,
            "passHash"
        );
        $command = new LoginCommand($email, "mypass");
        $token = new AuthToken("some.jwt.token");
        $this->userRepository->getByEmailOrFail($email)->willReturn($user);
        $this->hasher->hash("mypass")->willReturn("passHash");
        $this->authTokenGenerator->__invoke($user)->willReturn($token);
        $this->refreshTokenGenerator->__invoke($user)->willReturn($token);
        $this->userRepository->save(Argument::type(User::class));

        $useCase = $this->getUseCase();
        $result = $useCase($command);

        $this->assertInstanceOf(LoginResult::class, $result);
        $this->assertEquals($token->value, $result->token);
    }

    public function test_returns_refresh_when_credentials_are_valid()
    {
        $email = new Email("existing@email.com");
        $user = new User(
            new Id(1),
            $email,
            "passHash"
        );
        $command = new LoginCommand($email, "mypass");
        $this->userRepository->getByEmailOrFail($email)->willReturn($user);
        $this->hasher->hash("mypass")->willReturn("passHash");
        $token = new AuthToken("some.jwt.token");
        $this->authTokenGenerator->__invoke($user)->willReturn($token);
        $refresh = new AuthToken("refresh.stuff.inside");
        $this->refreshTokenGenerator->__invoke($user)->willReturn($refresh);
        $this->userRepository->save(Argument::type(User::class));

        $useCase = $this->getUseCase();
        $result = $useCase($command);

        $this->assertInstanceOf(LoginResult::class, $result);
        $this->assertEquals($refresh->value, $result->refresh);
    }

    public function test_stores_refresh_token()
    {
        $email = new Email("existing@email.com");
        $user = new User(
            new Id(1),
            $email,
            "passHash"
        );
        $command = new LoginCommand($email, "mypass");
        $refreshToken = new AuthToken("some.jwt.token");
        $this->userRepository->getByEmailOrFail($email)->willReturn($user);
        $this->hasher->hash("mypass")->willReturn("passHash");
        $this->authTokenGenerator->__invoke($user)->willReturn($refreshToken);
        $this->refreshTokenGenerator->__invoke($user)->willReturn($refreshToken);
        $user->setRefreshToken($refreshToken);
        $this->userRepository->save($user)
            ->shouldBeCalled();

        $useCase = $this->getUseCase();
        $useCase($command);
    }

    private function getUseCase(): LoginUseCase
    {
        return new LoginUseCase(
            $this->userRepository->reveal(),
            $this->hasher->reveal(),
            $this->authTokenGenerator->reveal(),
            $this->refreshTokenGenerator->reveal()
        );
    }
}
