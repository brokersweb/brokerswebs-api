<?php

namespace App\Http\Controllers;

use App\Models\BuildingCompany;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class BuildingCompanyController extends Controller
{

 

    public function index()
    {
        $buildings = BuildingCompany::select('id', 'name')->orderBy('name', 'ASC')->get();
        return $this->successResponse($buildings);
    }

}
