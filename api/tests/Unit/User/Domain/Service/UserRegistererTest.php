<?php

namespace Test\Unit\User\Domain\Service;

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

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);
    }

    public function test_saves_built_user()
    {
        $email = 'some@email.com';
        $password = 'somePass';
        $command = new RegisterUserCommand($email, $password);
        $user = new User(new Email($email), $password);
        $this->userRepository->getByEmail(new Email($email))
            ->shouldBeCalled()
            ->willReturn(null);
        $this->userRepository->save($user)
            ->shouldBeCalled();
        $service = new UserRegisterer($this->userRepository->reveal());

        $service->execute($command);
    }

    public function test_throws_exception_if_email_in_use()
    {
        $this->expectException(UserAlreadyExistsException::class);

        $email = 'existing@email.com';
        $password = "mypassword";
        $command = new RegisterUserCommand($email, $password);
        $user = new User(new Email($email), $password);
        $this->userRepository->getByEmail(new Email($email))
            ->shouldBeCalled()
            ->willReturn($user);
        $this->userRepository->save(Argument::any())
            ->shouldNotBeCalled();
        $service = new UserRegisterer($this->userRepository->reveal());

        $service->execute($command);
    }
}
