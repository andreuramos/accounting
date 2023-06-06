<?php

namespace Test\Unit\User\Application\UseCase;

use App\User\Application\Command\RegisterUserCommand;
use App\User\Application\UseCase\RegisterUserUseCase;
use App\User\Domain\Service\UserCreator;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class RegisterUserUseCaseTest extends TestCase
{
    use ProphecyTrait;

    private $userRegisterer;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRegisterer = $this->prophesize(UserCreator::class);
    }

    public function test_user_is_registered()
    {
        $this->userRegisterer->execute(Argument::any())->shouldBeCalled();
        $command = new RegisterUserCommand('my@email.com', "123");
        $usecase = new RegisterUserUseCase($this->userRegisterer->reveal());

        $usecase($command);
    }
}
