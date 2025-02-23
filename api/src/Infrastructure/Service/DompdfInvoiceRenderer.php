<?php

namespace App\Infrastructure\Service;

use App\Domain\Entities\InvoiceAggregate;
use App\Domain\Service\InvoiceRendererInterface;
use App\Domain\ValueObject\InvoiceLine;
use Dompdf\Dompdf;
use Dompdf\Options;
use League\Plates\Engine;

class DompdfInvoiceRenderer implements InvoiceRendererInterface
{
    public function __invoke(InvoiceAggregate $invoiceAggregate): string
    {
        $options = new Options();
        $options->setIsRemoteEnabled(true);

        $pdf = new Dompdf($options);
        $pdf->loadHtml($this->renderTemplate($invoiceAggregate));
        $pdf->setPaper('A4');
        $pdf->render();

        return $pdf->output();
    }

    private function renderTemplate(InvoiceAggregate $invoiceAggregate): string
    {
        $template = new Engine(__DIR__ . '/InvoiceTemplate');

        $invoice_lines = [];
        /** @var InvoiceLine $invoice_line */
        foreach ($invoiceAggregate->invoiceLines() as $invoice_line) {
            $invoice_lines[] = [
                'concept' => $invoice_line->product,
                'price' => $invoice_line->amount,
                'quantity' => $invoice_line->quantity,
                'vat' => $invoice_line->vatAmount(),
                'vat_percent' => $invoice_line->vat_percentage,
                'line_total' => $invoice_line->totalAmount(),
            ];
        }

        return $template->render('invoice', [
            'invoiceNumber' => (string)$invoiceAggregate->invoiceNumber(),
            'date' => $invoiceAggregate->invoiceDate()->format('d-m-Y'),
            'emitter' => [
                'tax_name' => $invoiceAggregate->emitterTaxName(),
                'tax_id' => $invoiceAggregate->emitterTaxNumber(),
                'address' => $invoiceAggregate->emitterTaxAddress(),
            ],
            'receiver' => [
                'tax_name' => $invoiceAggregate->receiverTaxName(),
                'tax_id' => $invoiceAggregate->receiverTaxNumber(),
                'address' => $invoiceAggregate->receiverTaxAddress(),
            ],
            'lines' => $invoice_lines,
            'baseAmount' => $invoiceAggregate->baseAmount(),
            'vatAmount' => $invoiceAggregate->vatAmount(),
            'totalAmount' => $invoiceAggregate->totalAmount(),
            'bankAccount' => "ES9701280581210100059701",
            'logo' => $this->getLogoContents(),
        ]);
    }

    private function getLogoContents(): string
    {
        $image = base64_encode(file_get_contents(__DIR__ . "/../../../uploads/moixa.png"));
        
        return "data:image/png;base64," . $image;
        
    }
}
