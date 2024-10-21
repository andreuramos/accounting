<?php

namespace App\Application\UseCase\GetInvoices;

use App\Domain\ValueObject\Id;

class GetInvoicesCommand
{
    public function __construct(public readonly Id $userId)
    {
    }
}