<?php

namespace App\Http\Controllers;

use App\Models\Invoice\Invoice;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    //


    public function scannerDownload($id)
    {
        $invoice = Invoice::find($id);
        // dd($invoice);

        $pdfBase64 = $invoice->doc_file;
        $pdfContent = base64_decode($pdfBase64);

        $fileName = "Estado de cuenta_{$invoice->id}.pdf";

        return response($pdfContent)
            ->header('Content-Type', 'application/pdf')
            ->header('Content-Disposition', "attachment; filename={$fileName}");
    }
}
