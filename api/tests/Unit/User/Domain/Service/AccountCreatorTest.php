<?php

namespace Test\Unit\User\Domain\Service;

use App\Domain\Account;
use App\Domain\AccountRepositoryInterface;
use App\Domain\Id;
use App\Domain\User;
use App\Domain\UserRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Service\AccountCreator;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class AccountCreatorTest extends TestCase
{
    use ProphecyTrait;

    private $userRepository;
    private $accountRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->userRepository = $this->prophesize(UserRepositoryInterface::class);
        $this->accountRepository = $this->prophesize(AccountRepositoryInterface::class);
    }

    public function test_account_is_created_and_saved()
    {
        $email = new Email("some@email.com");
        $user = new User(new Id(1), $email, '123');
        $this->userRepository->getByEmailOrFail($email)
            ->willReturn($user);
        $this->accountRepository->save(Argument::type(Account::class))
            ->shouldBeCalled();
        $service = new AccountCreator(
            $this->userRepository->reveal(),
            $this->accountRepository->reveal(),
        );

        $service("some@email.com");
    }
}
