<?php

namespace Test\Unit\User\Application\UseCase;

use App\Shared\Application\Service\HasherInterface;
use App\Shared\Domain\ValueObject\Id;
use App\User\Application\Auth\RefreshTokenGeneratorInterface;
use App\User\Application\Command\LoginCommand;
use App\User\Application\Result\LoginResult;
use App\User\Application\Auth\AuthTokenGeneratorInterface;
use App\User\Application\UseCase\LoginUseCase;
use App\User\Domain\Entity\User;
use App\User\Domain\Exception\InvalidCredentialsException;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;
use App\User\Infrastructure\Auth\JWTToken;
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
        $this->userRepository->getByEmail($email)->willReturn(null);

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
        $this->userRepository->getByEmail($email)->willReturn($user);
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
        $token = new JWTToken("some.jwt.token");
        $this->userRepository->getByEmail($email)->willReturn($user);
        $this->hasher->hash("mypass")->willReturn("passHash");
        $this->authTokenGenerator->__invoke($user)->willReturn($token);
        $this->refreshTokenGenerator->__invoke($user)->willReturn($token);
        $this->userRepository->save(Argument::type(User::class));

        $useCase = $this->getUseCase();
        $result = $useCase($command);

        $this->assertInstanceOf(LoginResult::class, $result);
        $this->assertEquals($token, $result->token);
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
        $this->userRepository->getByEmail($email)->willReturn($user);
        $this->hasher->hash("mypass")->willReturn("passHash");
        $token = new JWTToken("some.jwt.token");
        $this->authTokenGenerator->__invoke($user)->willReturn($token);
        $refresh = new JWTToken("refresh.stuff.inside");
        $this->refreshTokenGenerator->__invoke($user)->willReturn($refresh);
        $this->userRepository->save(Argument::type(User::class));

        $useCase = $this->getUseCase();
        $result = $useCase($command);

        $this->assertInstanceOf(LoginResult::class, $result);
        $this->assertEquals($refresh, $result->refresh);
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
        $refreshToken = new JWTToken("some.jwt.token");
        $this->userRepository->getByEmail($email)->willReturn($user);
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
