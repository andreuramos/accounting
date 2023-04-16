<?php

namespace Test\Unit\User\Application\UseCase;

use App\Shared\Application\Service\HasherInterface;
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
use Prophecy\PhpUnit\ProphecyTrait;

class LoginUseCaseTest extends TestCase
{
    use ProphecyTrait;
    private $userRepository;
    private $hasher;
    private $authTokenGenerator;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);
        $this->hasher = $this->prophesize(HasherInterface::class);
        $this->authTokenGenerator = $this->prophesize(AuthTokenGeneratorInterface::class);
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
            $email,
            "passHash"
        );
        $command = new LoginCommand($email, "mypass");
        $token = new JWTToken("some.jwt.token");
        $this->userRepository->getByEmail($email)->willReturn($user);
        $this->hasher->hash("mypass")->willReturn("passHash");
        $this->authTokenGenerator->__invoke($user)->willReturn($token);

        $useCase = $this->getUseCase();
        $result = $useCase($command);

        $this->assertInstanceOf(LoginResult::class, $result);
        $this->assertEquals($token, $result->token);
    }

    private function getUseCase(): LoginUseCase
    {
        return new LoginUseCase(
            $this->userRepository->reveal(),
            $this->hasher->reveal(),
            $this->authTokenGenerator->reveal()
        );
    }
}
