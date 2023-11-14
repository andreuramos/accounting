<?php

namespace Test\Unit\Domain\Service;

use App\Domain\Entities\TaxAgency303Form;
use App\Domain\Service\TaxAgency303Page0Renderer;
use App\Domain\Service\TaxAgency303FormRenderer;
use App\Domain\ValueObject\DeclarationPeriod;
use PHPUnit\Framework\TestCase;

class TaxAgency303FormRendererTest extends TestCase
{
    private TaxAgency303Page0Renderer $envelopeRenderer;

    public function setUp(): void
    {
        $this->envelopeRenderer = $this->createMock(TaxAgency303Page0Renderer::class);
    }

    public function test_empty_declaration(): void
    {
        $declaration = new TaxAgency303Form(
            'B07123',
            'My Company SL',
            2023,
            DeclarationPeriod::QUARTER(4)
        );
        $this->envelopeRenderer->expects($this->once())
            ->method('__invoke')
            ->with(303, 2023, DeclarationPeriod::QUARTER(4), '')
            ->willReturn('<envelope></envelope>');
        $service = $this->buildService();

        $output = $service($declaration);

        $this->assertEquals('<envelope></envelope>', $output);
    }

    /**
     * @return TaxAgency303FormRenderer
     */
    private function buildService(): TaxAgency303FormRenderer
    {
        return new TaxAgency303FormRenderer(
            $this->envelopeRenderer
        );
    }
}
