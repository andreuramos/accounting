<?php

namespace Test\Unit\Domain\Service;

use App\Domain\Entities\VatDeclaration;
use App\Domain\Service\DeclarationEnvelopeRenderer;
use App\Domain\Service\VatDeclarationRenderer;
use App\Domain\ValueObject\DeclarationPeriod;
use PHPUnit\Framework\TestCase;

class VatDeclarationRendererTest extends TestCase
{
    private DeclarationEnvelopeRenderer $envelopeRenderer;

    public function setUp(): void
    {
        $this->envelopeRenderer = $this->createMock(DeclarationEnvelopeRenderer::class);
    }

    public function test_empty_declaration(): void
    {
        $declaration = new VatDeclaration(
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
     * @return VatDeclarationRenderer
     */
    private function buildService(): VatDeclarationRenderer
    {
        return new VatDeclarationRenderer(
            $this->envelopeRenderer
        );
    }
}
