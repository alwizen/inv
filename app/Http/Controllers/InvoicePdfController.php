<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class InvoicePdfController extends Controller
{
    public function generate(Invoice $invoice)
    {
        $pdf = Pdf::loadView('invoice.pdf', ['invoice' => $invoice])
            ->setPaper('a4')
            ->setOption('margin-top', 10)
            ->setOption('margin-bottom', 10);

        return $pdf->stream('Invoice-' . $invoice->invoice_number . '.pdf');
    }
}
