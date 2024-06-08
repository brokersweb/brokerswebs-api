<?php

namespace App\Http\Controllers\Panel;

use App\Http\Controllers\Controller;
use App\Models\Base\FloorType;

class FloorTypeController extends Controller
{

    public function index()
    {
        $floors = FloorType::all();
        return $this->successResponse($floors);
    }
}
