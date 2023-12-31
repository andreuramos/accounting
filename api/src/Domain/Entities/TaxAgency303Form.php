<?php

namespace App\Domain\Entities;

use App\Domain\ValueObject\AccruedTax;
use App\Domain\ValueObject\DeclarationPeriod;
use App\Domain\ValueObject\DeductibleTax;
use App\Domain\ValueObject\Money;

class TaxAgency303Form
{
    public const TYPE_COMPENSATION_REQUEST = "compensation_request";
    public const TYPE_NO_ACTIVITY_OR_ZERO_RESULT = "no_activity_zero_result";
    public const TYPE_INCOME = "income";

    public function __construct(
        public readonly string $taxId,
        public readonly string $taxName,
        public readonly int $year,
        public readonly DeclarationPeriod $period,
        public readonly AccruedTax $accruedTax,
        public readonly DeductibleTax $deductibleTax,
        public readonly string $IBAN,
        public readonly Money $pendingAmountFromPreviousPeriod = new Money(0),
    ) {
    }

    public function declarationType(): string
    {
        $liquidation_result = $this->accruedTax->tax - $this->deductibleTax->tax;
        
        if ($liquidation_result < 0) {
            return self::TYPE_COMPENSATION_REQUEST;
        }
        
        if ($liquidation_result === 0 || $this->pendingAmountFromPreviousPeriod->amountCents > $liquidation_result) {
            return self::TYPE_NO_ACTIVITY_OR_ZERO_RESULT;
        }
        
        return self::TYPE_INCOME;
    }

    public function taxDue(): int
    {
        return $this->accruedTax->tax - $this->deductibleTax->tax;
    }
}
