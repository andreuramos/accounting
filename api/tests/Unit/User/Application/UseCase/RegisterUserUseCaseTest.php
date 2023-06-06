<?php

namespace Test\Unit\User\Application\UseCase;

use App\User\Application\Command\RegisterUserCommand;
use App\User\Application\UseCase\RegisterUserUseCase;
use App\User\Domain\Service\AccountCreator;
use App\User\Domain\Service\UserCreator;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class RegisterUserUseCaseTest extends TestCase
{
    use ProphecyTrait;

    private $userCreator;
    private $accountCreator;

    public function setUp(): void
    {
        parent::setUp();
        $this->userCreator = $this->prophesize(UserCreator::class);
        $this->accountCreator = $this->prophesize(AccountCreator::class);
    }

    public function test_user_is_registered()
    {
        $this->userCreator->__invoke(Argument::any())->shouldBeCalled();
        $command = new RegisterUserCommand('my@email.com', "123");
        $usecase = $this->getUseCase();

        $usecase($command);
    }

    public function test_account_is_created()
    {
        $command = new RegisterUserCommand('your@email.com', '123');
        $this->accountCreator->__invoke($command->email())->shouldBeCalled();
        $usecase = $this->getUseCase();

        $usecase($command);
    }

    private function getUseCase(): RegisterUserUseCase
    {
        return new RegisterUserUseCase(
            $this->userCreator->reveal(),
            $this->accountCreator->reveal(),
        );
    }
}
