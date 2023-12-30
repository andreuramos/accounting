<?php

namespace App\Domain\Service;

use App\Domain\ValueObject\DeductibleTax;
use App\Domain\ValueObject\DeclarationPeriod;
use App\Domain\ValueObject\AccruedTax;
use App\Domain\ValueObject\Money;

class TA303FormRenderer
{
    private const COMPENSATION_REQUEST = "C";
    private const NO_ACTIVITY_OR_ZERO_RESULT = "N";
    private const INCOME = "I";
    private const STATE_ADMIN_ATTRIBUTABLE_PERCENT = 100_00;

    public function __invoke(
        int $year,
        DeclarationPeriod $period,
        string $tax_id,
        string $tax_name,
        AccruedTax $accruedTax,
        DeductibleTax $deductibleTax,
        string $IBAN,
        Money $pendingFromPreviousPeriods = new Money(0),
    ): string {
        return implode('', [
            "<T3030{$year}{$period}0000>",
            $this->generateAuxTag(),
            $this->generatePage1(
                $tax_id,
                $tax_name,
                $year,
                $period,
                $accruedTax,
                $deductibleTax,
                $pendingFromPreviousPeriods,
            ),
            $this->generatePage3(
                $accruedTax,
                $deductibleTax,
                $pendingFromPreviousPeriods,
                $IBAN,
            ),
            "</T3030{$year}{$period}0000>"
        ]);
    }

    private function padding($width): string
    {
        return str_repeat(' ', $width);
    }

    private function generateAuxTag(): string
    {
        return implode('', [
            "<AUX>",
            $this->padding(70),
            'v2.0',
            $this->padding(4),
            '12345678Z',
            $this->padding(213),
            '</AUX>',
        ]);
    }

    private function generatePage1(
        string $tax_id,
        string $tax_name,
        int $year,
        DeclarationPeriod $period,
        AccruedTax $accruedTax,
        DeductibleTax $deductibleTax,
        Money $pendingFromPreviousPeriods,
    ): string {
        return implode('', [
            "<T30301000>",
            $this->generateIdentificationData(
                $tax_id,
                $tax_name,
                $year,
                $period,
                $accruedTax,
                $deductibleTax,
                $pendingFromPreviousPeriods,
            ),
            $this->generateAccruedTaxData($accruedTax),
            $this->generateDeductibleTaxData($deductibleTax, $accruedTax),
            $this->padding(613),
            "</T30301000>",
        ]);
    }

    private function generateIdentificationData(
        string $tax_id,
        string $tax_name,
        int $year,
        DeclarationPeriod $period,
        AccruedTax $accruedTax,
        DeductibleTax $deductibleTax,
        Money $pendingFromOtherPeriods,
    ): string {
        return implode('', [
            $this->padding(1),
            $this->calculateDeclarationType($accruedTax, $deductibleTax, $pendingFromOtherPeriods),
            $tax_id,
            $tax_name,
            $this->padding(80 - strlen($tax_name)),
            $year,
            $period,
            "2",
            "2",
            "3",
            "2",
            "2",
            "2",
            "2",
            "2",
            "2",
            $this->padding(8),
            " ",
            "2",
            "0",
            "0",
        ]);
    }

    private function generateAccruedTaxData(
        AccruedTax $accruedTax,
    ): string {
        return implode('', [
            $this->fillNumber(0, 17),
            $this->fillNumber(4_00, 5),
            $this->fillNumber(0, 17),
            $this->fillNumber(0, 17),
            $this->fillNumber(10_00, 5),
            $this->fillNumber(0, 17),
            $this->fillNumber($accruedTax->base, 17),
            $this->fillNumber($accruedTax->rate, 5),
            $this->fillNumber($accruedTax->tax, 17),
            $this->fillNumber(0, 102),
            $this->fillNumber(0, 17),
            $this->fillNumber(50, 5),
            $this->fillNumber(0, 17),
            $this->fillNumber(0, 17),
            $this->fillNumber(1_40, 5),
            $this->fillNumber(0, 17),
            $this->fillNumber(0, 17),
            $this->fillNumber(5_20, 5),
            $this->fillNumber(0, 17),
            $this->fillNumber(0, 17),
            $this->fillNumber(0, 17),
            $this->fillNumber($accruedTax->tax, 17),
        ]);
    }

    private function generateDeductibleTaxData(
        DeductibleTax $deductibleTax,
        AccruedTax $accruedTax,
    ): string {
        $taxResult = $accruedTax->tax - $deductibleTax->tax;

        return implode('', [
            $this->fillNumber($deductibleTax->base, 17),
            $this->fillNumber($deductibleTax->tax, 17),
            $this->fillNumber(0, 255),
            $this->fillNumber($deductibleTax->tax, 17),
            $this->fillNumber($taxResult, 17),
        ]);
    }

    private function generatePage3(
        AccruedTax $accruedTax,
        DeductibleTax $deductibleTax,
        Money $pendingFromPreviousPeriods,
        string $IBAN,
    ): string {
        return implode('', [
            "<T30303000>",
            $this->fillNumber(0, 170),
            $this->generateResult($accruedTax, $deductibleTax, $pendingFromPreviousPeriods),
            $this->generateOtherData($IBAN),
            "</T30303000>",
        ]);
    }

    private function generateResult(
        AccruedTax $accruedTax,
        DeductibleTax $deductibleTax,
        Money $pendingFromPreviousPeriods,
    ): string {
        $currentPeriodTaxDue = $accruedTax->tax - $deductibleTax->tax;
        $toBeCompensatedInThisPeriod = $this->calculateMaxAmountToCompensateFromPreviousPeriods(
            $pendingFromPreviousPeriods->amountCents,
            $currentPeriodTaxDue,
        );
        $remainingForNextPeriods = $pendingFromPreviousPeriods->amountCents - $toBeCompensatedInThisPeriod;

        return implode('', [
            $this->fillNumber(0, 17), // Regularización cuotas art80.cinco.5 LIVA
            $this->fillNumber($currentPeriodTaxDue, 17), // suma de resultados
            $this->fillNumber(self::STATE_ADMIN_ATTRIBUTABLE_PERCENT, 5),
            $this->fillNumber($currentPeriodTaxDue, 17),
            $this->fillNumber(0, 17), //Iva a la importación liquidado por Aduanas
            $this->fillNumber($pendingFromPreviousPeriods->amountCents, 17),
            $this->fillNumber($toBeCompensatedInThisPeriod, 17),
            $this->fillNumber($remainingForNextPeriods, 17),
            $this->fillNumber(0, 17),
            $this->fillNumber($currentPeriodTaxDue - $toBeCompensatedInThisPeriod, 17),
            $this->fillNumber(0, 17),
            $this->fillNumber($currentPeriodTaxDue - $toBeCompensatedInThisPeriod, 17),
        ]);
    }

    private function fillNumber(int $number, int $size): string
    {
        $formattedNumber = str_repeat('0', $size - strlen(abs($number))) . abs($number);

        if ($number < 0) {
            $formattedNumber[0] = "N";
        }

        return $formattedNumber;
    }

    private function generateOtherData(string $IBAN): string
    {
        return implode('', [
            ' ',
            $this->padding(13),
            ' ',
            $this->padding(11),
            $IBAN,
            $this->padding(765),
        ]);
    }

    private function calculateDeclarationType(
        AccruedTax $accruedTax,
        DeductibleTax $deductibleTax,
        Money $pendingFromPreviousPeriods,
    ): string {
        $liquidationResult = $accruedTax->tax - $deductibleTax->tax;
        if ($liquidationResult < 0) {
            return self::COMPENSATION_REQUEST;
        }

        if ($liquidationResult === 0 || $pendingFromPreviousPeriods->amountCents > $liquidationResult) {
            return self::NO_ACTIVITY_OR_ZERO_RESULT;
        }

        return self::INCOME;
    }

    private function calculateMaxAmountToCompensateFromPreviousPeriods(
        int $pendingFromPreviousPeriods,
        int $currentPeriodResult,
    ): int {
        if ($pendingFromPreviousPeriods == 0 || $currentPeriodResult <= 0) {
            return 0;
        }

        if ($pendingFromPreviousPeriods >= $currentPeriodResult) {
            return $currentPeriodResult;
        }

        return $pendingFromPreviousPeriods;
    }
}
