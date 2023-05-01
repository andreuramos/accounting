<?php

namespace Test\Unit\Transaction\Application\UseCase;

use App\Shared\Domain\ValueObject\Id;
use App\Transaction\Application\Command\GetUserExpensesCommand;
use App\Transaction\Application\UseCase\GetUserExpensesUseCase;
use App\Transaction\Domain\Model\ExpenseRepositoryInterface;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class GetUserExpensesUseCaseTest extends TestCase
{
    use ProphecyTrait;

    private $expenseRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->expenseRepository = $this->prophesize(ExpenseRepositoryInterface::class);
    }

    public function test_no_expenses()
    {
        $user = new User(new Id(1), new Email("getexpenses@usecase.app"), "");
        $this->expenseRepository->getByUser($user)->willReturn([]);
        $useCase = new GetUserExpensesUseCase();
        $command = new GetUserExpensesCommand($user);

        $result = $useCase($command);

        $this->assertCount(0, $result->expenses);
    }
}
