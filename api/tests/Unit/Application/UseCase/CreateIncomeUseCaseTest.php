<?php

namespace Test\Unit\Application\UseCase;

use App\Application\UseCase\CreateIncome\CreateIncomeCommand;
use App\Application\UseCase\CreateIncome\CreateIncomeUseCase;
use App\Domain\Entities\Income;
use App\Domain\Entities\User;
use App\Domain\Repository\IncomeRepositoryInterface;
use App\Domain\ValueObject\Email;
use App\Domain\ValueObject\Id;
use App\Domain\ValueObject\Money;
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
