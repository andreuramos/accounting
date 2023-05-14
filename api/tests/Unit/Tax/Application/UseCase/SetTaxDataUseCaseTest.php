<?php

namespace Test\Unit\Tax\Application\UseCase;

use App\Shared\Domain\ValueObject\Id;
use App\Tax\Application\Command\SetUserTaxDataCommand;
use App\Tax\Application\UseCase\SetUserTaxDataUseCase;
use App\Tax\Domain\Entity\TaxData;
use App\Tax\Domain\Model\TaxDataAggregateRepositoryInterface;
use App\User\Domain\Entity\User;
use App\User\Domain\ValueObject\Email;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Prophecy\PhpUnit\ProphecyTrait;

class SetTaxDataUseCaseTest extends TestCase
{
    use ProphecyTrait;

    private $taxDataRepository;

    public function setUp(): void
    {
        parent::setUp();
        $this->taxDataRepository = $this->prophesize(TaxDataAggregateRepositoryInterface::class);
    }

    public function test_empty_name_fails()
    {
        $user = new User(new Id(1), new Email("my@email.com"), "");
        $command = new SetUserTaxDataCommand(
            $user, "", "43186322G", "Fake street 123", "07013"
        );
        $useCase = $this->getUseCase();

        $this->expectException(\InvalidArgumentException::class);

        $useCase($command);
    }

    public function test_stores_it_in_repo()
    {
        $user = new User(new Id(1), new Email("my@email.com"), "");
        $command = new SetUserTaxDataCommand(
            $user, "Moixa Brewing", "B076546846", "Fake street 123", "07013"
        );
        $this->taxDataRepository->save(Argument::type(TaxData::class))
            ->shouldBeCalled();
        $useCase = $this->getUseCase();

        $useCase($command);
    }

    private function getUseCase(): SetUserTaxDataUseCase
    {
        return new SetUserTaxDataUseCase(
            $this->taxDataRepository->reveal(),
        );
    }
}
