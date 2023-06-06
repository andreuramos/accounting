<?php

namespace Test\Unit\User\Application\UseCase;

use App\User\Application\Command\RegisterUserCommand;
use App\User\Application\UseCase\RegisterUserUseCase;
use App\User\Domain\Model\AccountRepositoryInterface;
use App\User\Domain\Service\UserCreator;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class RegisterUserUseCaseTest extends TestCase
{
    use ProphecyTrait;

    private $userCreator;
    private $accountRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->userCreator = $this->prophesize(UserCreator::class);
        $this->accountRepository = $this->prophesize(AccountRepositoryInterface::class);
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
        $this->accountRepository->createForUser($command->email())->shouldBeCalled();
        $usecase = $this->getUseCase();

        $usecase($command);
    }

    private function getUseCase(): RegisterUserUseCase
    {
        return new RegisterUserUseCase(
            $this->userCreator->reveal(),
            $this->accountRepository->reveal(),
        );
    }
}
