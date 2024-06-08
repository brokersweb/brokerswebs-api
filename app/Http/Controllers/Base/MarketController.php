<?php

namespace App\Http\Controllers\Base;

use App\Http\Controllers\Controller;
use App\Models\Base\Market;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class MarketController extends Controller
{

    public function index()
    {
        $markets = Market::all();
        return $this->successResponse($markets, Response::HTTP_OK);
    }
}
