<?php

namespace App\Domain;

interface InvoiceRepositoryInterface
{
    public function save(Invoice $invoice): Id;
    public function getLastEmittedByBusiness(Business $business): ?Invoice;
}
