<?php

namespace Test\Unit\Transaction\Application\UseCase;

use App\Shared\Domain\ValueObject\Id;
use App\Transaction\Application\Command\CreateIncomeCommand;
use App\Transaction\Application\UseCase\CreateIncomeUseCase;
use App\Transaction\Domain\Entity\Income;
use App\Transaction\Domain\Model\IncomeRepositoryInterface;
use App\Transaction\Domain\ValueObject\Money;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\Email;
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
        $commmand = new CreateIncomeCommand(
            $user,
            1000,
            "test repository is called with a new income",
            '2023-05-03',
        );
        $usecase = new CreateIncomeUseCase($this->incomeRepository->reveal());
        $expectedIncome = new Income(
            new Id(null),
            $user->id(),
            new Money(1000, "EUR"),
            "test repository is called with a new income",
            new \DateTime('2023-05-03'),
        );

        $this->incomeRepository->save($expectedIncome)->shouldBeCalled();

        $usecase($commmand);
    }
}
