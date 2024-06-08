<?php

namespace App\Http\Repositories;

use App\Models\CompanyConfiguration;
use App\Traits\ApiResponse;
use Illuminate\Config\Repository;
use Illuminate\Http\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CompanyConfigurationRepository extends Repository
{
    use ApiResponse;
    private $model;

    public function __construct()
    {
        $this->model = new CompanyConfiguration();
    }

    public function getConfiguration()
    {
        $configuration = $this->model->first();
        if ($configuration) {
            return response()->json($configuration, Response::HTTP_OK);
        }
        return $this->errorResponse('No se encontró la configuración de la empresa', Response::HTTP_NOT_FOUND);
    }

    public function create(Request $request)
    {
        $rulesData = Validator::make($request->all(), [
            'name' => 'required|max:70',
            'email' => 'required|email',
            'cellphone' => 'required|max:30',
            'phone' => 'nullable|max:30',
            'nit' => 'required|max:30',
            'city' => 'required|max:30',
            'department' => 'required|max:30',
        ]);

        if ($rulesData->fails()) {
            return $this->errorResponse($rulesData->errors(), Response::HTTP_BAD_REQUEST);
        }
        try {
            $configuration = $this->model->create($request->all());
            return $this->successResponse($configuration, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al guardar los datos', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update($request, $id)
    {
        $configuration = $this->model->find($id);
        if (!$configuration) {
            return $this->errorResponse('No se encontró la configuración de la empresa', Response::HTTP_NOT_FOUND);
        }
        $rulesData = Validator::make($request->all(), [
            'name' => 'required|max:70',
            'email' => 'required|email',
            'cellphone' => 'required|max:30',
            'phone' => 'nullable|max:30',
            'nit' => 'required|max:30',
            'city' => 'required|max:30',
            'department' => 'required|max:30',
        ]);

        if ($rulesData->fails()) {
            return $this->errorResponse($rulesData->errors(), Response::HTTP_BAD_REQUEST);
        }
        try {
            $configuration->update($request->all());
            return $this->successResponse($configuration, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al guardar los datos', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
