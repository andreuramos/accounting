<?php

namespace Test\Unit\User\Domain\Service;

use App\User\Application\Command\RegisterUserCommand;
use App\User\Domain\Entity\User;
use App\User\Domain\Model\UserRepositoryInterface;
use App\User\Domain\Service\UserRegisterer;
use App\User\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;
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
        $command = new RegisterUserCommand($email);
        $user = new User(new Email($email));
        $this->userRepository->save($user)
            ->shouldBeCalled();
        $service = new UserRegisterer($this->userRepository->reveal());

        $service->execute($command);
    }
}
