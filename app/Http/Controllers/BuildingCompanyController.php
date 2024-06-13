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

    public function store(Request $request)
    {
        $rules = [
            'name' => 'required|max:255|unique:building_companies,name',
        ];
        $this->validate($request, $rules);

        try {
            BuildingCompany::create($request->all());
            return $this->successResponseWithMessage('Constructora agregada', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al agregar la constructora', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
