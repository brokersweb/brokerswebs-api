<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Base\CuisineType;

class CuisineTypeController extends Controller
{


    public function index()
    {
        $cousines = CuisineType::all();
        return $this->successResponse($cousines);
    }
}
