<?php

namespace App\Application\UseCase\GetInvoices;

use App\Domain\ValueObject\Id;

class GetInvoicesCommand
{
    public function __construct(
        public readonly Id $accountId,
        public readonly ?\DateTime $fromDate = null,
        public readonly ?\DateTime $toDate = null,
        public readonly ?string $emitter_vat_number = null,
        public readonly ?string $receiver_vat_number = null,
    ) {
    }
}
