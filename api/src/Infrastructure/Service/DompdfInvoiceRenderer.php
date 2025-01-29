<?php

namespace App\Infrastructure\Service;

use App\Domain\Entities\InvoiceAggregate;
use App\Domain\Service\InvoiceRendererInterface;
use Dompdf\Dompdf;

class DompdfInvoiceRenderer implements InvoiceRendererInterface
{
    public function __invoke(InvoiceAggregate $invoiceAggregate): string
    {
        $pdf = new Dompdf();
        $pdf->loadHtml("Invoice " . $invoiceAggregate->invoiceNumber());
        $pdf->render();

        return $pdf->output();
    }
}
