<?php

namespace App\Http\Controllers;

use App\Helpers\NumberToWords;
use Illuminate\Http\Request;

class InvoiceController extends Controller
{
    //

    public function getPeso()
    {
        $inWords = NumberToWords::toPesos(1354000);
        return response()->json(['pesos' => $inWords]);
    }
}
