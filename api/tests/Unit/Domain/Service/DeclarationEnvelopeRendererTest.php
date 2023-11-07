<?php

namespace Test\Unit\Domain\Service;

use App\Domain\Service\DeclarationEnvelopeRenderer;
use App\Domain\ValueObject\DeclarationPeriod;
use PHPUnit\Framework\TestCase;

class DeclarationEnvelopeRendererTest extends TestCase
{
    public function test_303_envelope(): void
    {
        $renderer = new DeclarationEnvelopeRenderer(303);

        $output = $renderer(2023, DeclarationPeriod::QUARTER(1),"DUMMYCONTENT");

        $expected = '<T303020231T0000><AUX>                                                                      '.
            'v1.0                                                                                                 '.
            '12345678Z                                                                                          '.
            '                                                                                                    '.
            '                       </AUX>DUMMYCONTENT</T303020231T0000>';
        $this->assertEquals($expected, $output);
    }
}
