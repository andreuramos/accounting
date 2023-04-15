<?php

namespace Test\Unit\User\Application\UseCase;

use App\User\Application\Command\LoginCommand;
use App\User\Application\UseCase\LoginUseCase;
use App\User\Domain\Exception\InvalidCredentialsException;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class LoginUseCaseTest extends TestCase
{
    use ProphecyTrait;
    private $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);
    }

    public function test_throws_exception_if_user_not_found()
    {
        $email = new Email("not@existing.com");
        $this->userRepository->getByEmail($email)->willReturn(null);
        $command = new LoginCommand($email, "mypass");

        $this->expectException(InvalidCredentialsException::class);

        $useCase = new LoginUseCase($this->userRepository->reveal());
        $useCase($command);
    }
}
