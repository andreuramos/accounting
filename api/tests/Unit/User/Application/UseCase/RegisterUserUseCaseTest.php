<?php

namespace Test\Unit\User\Application\UseCase;

use App\Application\Service\AccountCreator;
use App\Application\Service\UserCreator;
use App\Application\UseCase\RegisterUser\RegisterUserCommand;
use App\Application\UseCase\RegisterUser\RegisterUserUseCase;
use App\Domain\Entities\Account;
use App\Domain\Entities\User;
use App\Domain\Repository\AccountRepositoryInterface;
use App\Domain\Repository\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class RegisterUserUseCaseTest extends TestCase
{
    use ProphecyTrait;

    private $userCreator;
    private $accountCreator;
    private $userRepository;
    private $createdUser;
    private $accountRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->userCreator = $this->prophesize(UserCreator::class);
        $this->accountCreator = $this->prophesize(AccountCreator::class);
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);
        $this->createdUser = $this->prophesize(User::class);
        $this->accountRepository = $this->prophesize(AccountRepositoryInterface::class);

        $this->userRepository->save(Argument::type(User::class));
        $this->userRepository->getByEmailOrFail(Argument::type(Email::class))
            ->willReturn($this->createdUser->reveal());
        $this->accountRepository->getByOwnerEmailOrFail(Argument::type(Email::class))
            ->willReturn(new Account(new Id(1), new Id(1)));
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

    public function test_user_account_is_assigned()
    {
        $email = new Email('account@email.com');
        $command = new RegisterUserCommand($email->toString(), "123");

        $userId = new Id(2);
        $this->createdUser->id()->willReturn($userId);
        $accountId = new Id(3);
        $account = new Account(
            $accountId,
            $userId,
        );
        $this->accountRepository->getByOwnerEmailOrFail($email)
            ->willReturn($account);
        $this->createdUser->setAccountId($accountId)->shouldBeCalled();
        $this->userRepository->save($this->createdUser)->shouldBeCalled();
        $usecase = $this->getUseCase();

        $usecase($command);
    }

    private function getUseCase(): RegisterUserUseCase
    {
        return new RegisterUserUseCase(
            $this->userCreator->reveal(),
            $this->accountCreator->reveal(),
            $this->userRepository->reveal(),
            $this->accountRepository->reveal(),
        );
    }
}
