<?php

namespace Test\Unit\Transaction\Application\UseCase;

use App\Domain\Id;
use App\Domain\Income;
use App\Domain\IncomeRepositoryInterface;
use App\Domain\Money;
use App\Domain\User;
use App\Domain\ValueObject\Email;
use App\UseCase\CreateIncome\CreateIncomeCommand;
use App\UseCase\CreateIncome\CreateIncomeUseCase;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class CreateIncomeUseCaseTest extends TestCase
{
    use ProphecyTrait;
    private $incomeRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->incomeRepository = $this->prophesize(IncomeRepositoryInterface::class);
    }

    public function test_repository_is_called_with_a_new_income()
    {
        $user = new User(new Id(1), new Email("createincome@usecase.test"), "");
        $user->setAccountId(new Id(2));
        $commmand = new CreateIncomeCommand(
            $user,
            new Id(2),
            1000,
            "test repository is called with a new income",
            '2023-05-03',
        );
        $usecase = new CreateIncomeUseCase($this->incomeRepository->reveal());
        $expectedIncome = new Income(
            new Id(null),
            $user->accountId(),
            new Money(1000, "EUR"),
            "test repository is called with a new income",
            new \DateTime('2023-05-03'),
        );

        $this->incomeRepository->save($expectedIncome)->shouldBeCalled();

        $usecase($commmand);
    }
}
