<?php

namespace App\Domain;

interface InvoiceLineRepositoryInterface
{
    public function save(InvoiceLine $line): void;
}
