<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class LowStockReportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $pdfPath;
    public $filename;

    public function __construct($pdfPath, $filename)
    {
        $this->pdfPath = $pdfPath;
        $this->filename = $filename;
    }

    public function build()
    {
        return $this->subject('Reporte de Materiales con Stock Bajo')
            ->view('emails.low-stock-notification')
            ->attach($this->pdfPath, [
                'as' => $this->filename,
                'mime' => 'application/pdf'
            ]);
    }
}
