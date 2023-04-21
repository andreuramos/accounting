<?php

namespace Test\Unit\User\Application\UseCase;

use App\Shared\Domain\ValueObject\Id;
use App\User\Application\UseCase\GetUserUseCase;
use App\User\Domain\Entity\User;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Domain\ValueObject\Email;
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
        $this->userRepository->getByEmail($email)
            ->willReturn($user);
        $useCase = new GetUserUseCase($this->userRepository->reveal());

        $result = $useCase($email);

        $this->assertArrayHasKey('email', $result);
        $this->assertArrayNotHasKey('passwordHash', $result);
    }
}
