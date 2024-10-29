<?php

namespace Test\Unit\Application\UseCase;

use App\Application\UseCase\GetInvoices\GetInvoicesCommand;
use App\Application\UseCase\GetInvoices\GetInvoicesUseCase;
use App\Domain\Criteria\InvoiceCriteria;
use App\Domain\Repository\InvoiceAggregateRepositoryInterface;
use App\Domain\ValueObject\Id;
use PHPUnit\Framework\TestCase;
use Prophecy\PhpUnit\ProphecyTrait;

class GetInvoicesUseCaseTest extends TestCase
{
    use ProphecyTrait;
    
    private $invoiceAggregateRepository;
    
    public function setUp(): void
    {
        $this->invoiceAggregateRepository = $this->prophesize(InvoiceAggregateRepositoryInterface::class);
    }
    
    public function test_calls_repo_with_no_filters(): void
    {
        $command = new GetInvoicesCommand(new Id(1));
        $expectedCriteria = new InvoiceCriteria();
        $expectedCriteria->filterByAccountId(new Id(1));
        $useCase = $this->buildUseCase();

        $this->invoiceAggregateRepository
            ->getByCriteria($expectedCriteria)
            ->shouldBeCalled();


        $useCase($command);
    }
    
    public function test_calls_repo_with_emitter_filter(): void
    {
        $command = new GetInvoicesCommand(
            accountId: new Id(1),
            emitterTaxNumber: "G071923012"
        );
        $expectedCriteria = new InvoiceCriteria();
        $expectedCriteria
            ->filterByAccountId(new Id(1))
            ->filterByEmitterTaxNumber("G071923012");
        $useCase = $this->buildUseCase();

        $this->invoiceAggregateRepository
            ->getByCriteria($expectedCriteria)
            ->shouldBeCalled();

        $useCase($command);
    }
    
    public function test_calls_repo_with_receiver_filter(): void
    {
        $command = new GetInvoicesCommand(
            accountId: new Id(1),
            receiverTaxNumber: "43186322G",
        );
        $expectedCriteria = new InvoiceCriteria();
        $expectedCriteria
            ->filterByAccountId(new Id(1))
            ->filterByReceiverTaxNumber("43186322G");
        $useCase = $this->buildUseCase();

        $this->invoiceAggregateRepository
            ->getByCriteria($expectedCriteria)
            ->shouldBeCalled();
        
        $useCase($command);
    }

    private function buildUseCase(): GetInvoicesUseCase
    {
        return new GetInvoicesUseCase($this->invoiceAggregateRepository->reveal());
    }
}