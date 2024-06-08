<?php

namespace App\Http\Controllers;

use App\Models\Coownership;
use App\Models\CoownershipDetail;
use Illuminate\Http\Response;

class CoownershipController extends Controller
{

    public function index()
    {
        $coownerships = Coownership::select('name', 'id')->get();
        return $this->successResponse($coownerships);
    }
    public function show($id)
    {
        $codetails = CoownershipDetail::where('coownership_id', $id)->first();
        return $this->successResponse($codetails);
    }
}
