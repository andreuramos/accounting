<?php

namespace Test\Unit\Domain\Entity;

use App\Domain\Entities\VatDeclaration;
use PHPUnit\Framework\TestCase;

class VatDeclarationTest extends TestCase
{
    public function test_instance_attributes(): void
    {
        $taxNumber = 'B07507518';
        $taxName = 'Cervesa Moixa SL';
        $year = 2023;
        $period = 1;

        $entity = new VatDeclaration(
            $taxNumber,
            $taxName,
            $year,
            $period,
        );

        $this->assertEquals($entity->taxNumber, $taxNumber);
        $this->assertEquals($entity->taxName, $taxName);
        $this->assertEquals($entity->year, $year);
        $this->assertEquals($entity->period, $period);
    }
}
