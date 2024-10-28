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
        $expectedCriteria->filterBy("account_id", new Id(1));
        
        $this->invoiceAggregateRepository
            ->getByCriteria($expectedCriteria)
            ->shouldBeCalled();
        
        $useCase = $this->buildUseCase();
        
        $useCase($command);
    }

    private function buildUseCase(): GetInvoicesUseCase
    {
        return new GetInvoicesUseCase($this->invoiceAggregateRepository->reveal());
    }
}