<?php

namespace Test\Unit\User\Application\UseCase;

use App\Domain\Id;
use App\Domain\User;
use App\Domain\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\UseCase\GetUser\GetUserUseCase;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class GetUserUseCaseTest extends TestCase
{
    use ProphecyTrait;

    private $userRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);
    }

    public function test_returns_array_with_email_and_no_password()
    {
        $email = new Email("my@email.com");
        $user = new User(new Id(1), $email, "");
        $this->userRepository->getByEmailOrFail($email)
            ->willReturn($user);
        $useCase = new GetUserUseCase($this->userRepository->reveal());

        $result = $useCase($email);

        $this->assertArrayHasKey('email', $result);
        $this->assertArrayNotHasKey('passwordHash', $result);
    }
}
