<?php

namespace Test\Unit\User\Application\UseCase;

use App\Shared\Application\Service\HasherInterface;
use App\User\Application\Command\LoginCommand;
use App\User\Application\UseCase\LoginUseCase;
use App\User\Domain\Entity\User;
use App\User\Domain\Exception\InvalidCredentialsException;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class LoginUseCaseTest extends TestCase
{
    use ProphecyTrait;
    private $userRepository;
    private $hasher;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);
        $this->hasher = $this->prophesize(HasherInterface::class);
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

    private function getUseCase(): LoginUseCase
    {
        return new LoginUseCase(
            $this->userRepository->reveal(),
            $this->hasher->reveal()
        );
    }
}
