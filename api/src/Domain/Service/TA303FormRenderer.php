<?php

namespace App\Domain\Service;

use App\Domain\Entities\TaxAgency303Form;
use App\Domain\ValueObject\DeductibleTax;
use App\Domain\ValueObject\DeclarationPeriod;
use App\Domain\ValueObject\AccruedTax;
use App\Domain\ValueObject\Money;

class TA303FormRenderer
{
    private const STATE_ADMIN_ATTRIBUTABLE_PERCENT = 100_00;
    private const DECLARATION_TYPE_MAP = [
        TaxAgency303Form::TYPE_COMPENSATION_REQUEST => "C",
        TaxAgency303Form::TYPE_INCOME => "I",
        TaxAgency303Form::TYPE_NO_ACTIVITY_OR_ZERO_RESULT => "N",
    ];
    private const REGULAR_VAT_RATE = 21_00;
    private const REDUCED_VAT_RATE = 10_00;
    private const SUPER_REDUCED_VAT_RATE = 4_00;

    private TaxAgency303Form $form;

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
        
        $this->form = new TaxAgency303Form(
            $tax_id,
            $tax_name,
            $year,
            $period,
            $accruedTax,
            $deductibleTax,
            $IBAN,
            $pendingFromPreviousPeriods,
        );
        
        return implode('', [
            "<T3030{$year}{$period}0000>",
            $this->generateAuxTag(),
            $this->generatePage1(),
            $this->generatePage3(),
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

    private function generatePage1(): string
    {
        return implode('', [
            "<T30301000>",
            $this->generateIdentificationData(),
            $this->generateAccruedTaxData(),
            $this->generateDeductibleTaxData(),
            $this->padding(613),
            "</T30301000>",
        ]);
    }

    private function generateIdentificationData(): string 
    {
        return implode('', [
            $this->padding(1),
            self::DECLARATION_TYPE_MAP[$this->form->declarationType()],
            $this->form->taxId,
            $this->form->taxName,
            $this->padding(80 - strlen($this->form->taxName)),
            $this->form->year,
            $this->form->period,
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

    private function generateAccruedTaxData(): string 
    {
        $regularVatAccruedBase = $this->form->accruedTax->base;
        $regularVatAccruedTax = $this->form->accruedTax->tax;
        
        return implode('', [
            $this->fillNumber(0, 17),
            $this->fillNumber(self::SUPER_REDUCED_VAT_RATE, 5),
            $this->fillNumber(0, 17),
            $this->fillNumber(0, 17),
            $this->fillNumber(self::REDUCED_VAT_RATE, 5),
            $this->fillNumber(0, 17),
            $this->fillNumber($regularVatAccruedBase, 17),
            $this->fillNumber(self::REGULAR_VAT_RATE, 5),
            $this->fillNumber($regularVatAccruedTax, 17),
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
            $this->fillNumber($this->form->accruedTax->tax, 17),
        ]);
    }

    private function generateDeductibleTaxData(): string
    {
        $taxResult = $this->form->accruedTax->tax - $this->form->deductibleTax->tax;

        return implode('', [
            $this->fillNumber($this->form->deductibleTax->base, 17),
            $this->fillNumber($this->form->deductibleTax->tax, 17),
            $this->fillNumber(0, 255),
            $this->fillNumber($this->form->deductibleTax->tax, 17),
            $this->fillNumber($taxResult, 17),
        ]);
    }

    private function generatePage3(): string 
    {
        return implode('', [
            "<T30303000>",
            $this->fillNumber(0, 170),
            $this->generateResult(),
            $this->generateOtherData(),
            "</T30303000>",
        ]);
    }

    private function generateResult(): string 
    {
        $currentPeriodTaxDue = $this->form->taxDue();
        $toBeCompensatedInThisPeriod = $this->form->maxAmountToCompensate();
        $pendingFromPreviousPeriods = $this->form->pendingAmountFromPreviousPeriod->amountCents;
        $remainingForNextPeriods = $pendingFromPreviousPeriods - $toBeCompensatedInThisPeriod;

        return implode('', [
            $this->fillNumber(0, 17), // Regularización cuotas art80.cinco.5 LIVA
            $this->fillNumber($currentPeriodTaxDue, 17), // suma de resultados
            $this->fillNumber(self::STATE_ADMIN_ATTRIBUTABLE_PERCENT, 5),
            $this->fillNumber($currentPeriodTaxDue, 17),
            $this->fillNumber(0, 17), //Iva a la importación liquidado por Aduanas
            $this->fillNumber($pendingFromPreviousPeriods, 17),
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

    private function generateOtherData(): string
    {
        return implode('', [
            ' ',
            $this->padding(13),
            ' ',
            $this->padding(11),
            $this->form->IBAN,
            $this->padding(765),
        ]);
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
