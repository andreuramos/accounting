<?php

namespace Test\Unit\Invoice\Application\UseCase;

use App\Invoice\Application\Command\CreateInvoiceCommand;
use App\Invoice\Application\UseCase\CreateInvoiceUseCase;
use App\Shared\Domain\ValueObject\Id;
use App\Transaction\Domain\Exception\IncomeNotFoundException;
use App\Transaction\Domain\Model\IncomeRepositoryInterface;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class CreateInvoiceUseCaseTest extends TestCase
{
    use ProphecyTrait;

    private $incomeRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->incomeRepository = $this->prophesize(IncomeRepositoryInterface::class);
    }

    public function test_fails_when_income_not_found()
    {
        $incomeId = new Id(123);
        $command = new CreateInvoiceCommand(
            $incomeId
        );
        $this->incomeRepository->getByIdOrFail($incomeId)
            ->shouldBeCalled()
            ->willThrow(IncomeNotFoundException::class);
        $useCase = $this->getInvoiceUseCase();

        $this->expectException(IncomeNotFoundException::class);

        $useCase($command);
    }

    /**
     * @return CreateInvoiceUseCase
     */
    private function getInvoiceUseCase(): CreateInvoiceUseCase
    {
        return new CreateInvoiceUseCase(
            $this->incomeRepository->reveal()
        );
    }
}
