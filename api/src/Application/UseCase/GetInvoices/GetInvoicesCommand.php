<?php

namespace App\Application\UseCase\GetInvoices;

use App\Domain\ValueObject\Id;

class GetInvoicesCommand
{
    public function __construct(
        public readonly Id $accountId,
        public readonly ?\DateTime $fromDate = null,
        public readonly ?\DateTime $toDate = null,
        public readonly ?string $emitterTaxNumber = null,
        public readonly ?string $receiverTaxNumber = null,
    ) {
    }
}
