<?php

namespace Test\Unit\User\Domain\Service;

use App\Shared\Application\Service\HasherInterface;
use App\Shared\Domain\ValueObject\Id;
use App\User\Application\Command\RegisterUserCommand;
use App\User\Domain\Entity\User;
use App\User\Domain\Exception\UserAlreadyExistsException;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Domain\Service\UserRegisterer;
use App\User\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class UserRegistererTest extends TestCase
{
    use ProphecyTrait;

    private $userRepository;
    private $passwordHasher;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);
        $this->passwordHasher = $this->prophesize(HasherInterface::class);
    }

    public function test_saves_built_user()
    {
        $email = 'some@email.com';
        $password = 'somePass';
        $hashedPassword = "hashedPassword";
        $command = new RegisterUserCommand($email, $password);
        $this->userRepository->getByEmail(new Email($email))
            ->shouldBeCalled()
            ->willReturn(null);
        $this->passwordHasher->hash($password)
            ->shouldBeCalled()
            ->willReturn($hashedPassword);
        $user = new User(new Id(null), new Email($email), $hashedPassword);
        $this->userRepository->save($user)
            ->shouldBeCalled();
        $service = new UserRegisterer(
            $this->userRepository->reveal(),
            $this->passwordHasher->reveal()
        );

        $service->execute($command);
    }

    public function test_throws_exception_if_email_in_use()
    {
        $this->expectException(UserAlreadyExistsException::class);

        $email = 'existing@email.com';
        $password = "mypassword";
        $command = new RegisterUserCommand($email, $password);
        $user = new User(new Id(null), new Email($email), $password);
        $this->userRepository->getByEmail(new Email($email))
            ->shouldBeCalled()
            ->willReturn($user);
        $this->userRepository->save(Argument::any())
            ->shouldNotBeCalled();
        $service = new UserRegisterer(
            $this->userRepository->reveal(),
            $this->passwordHasher->reveal()
        );

        $service->execute($command);
    }
}
