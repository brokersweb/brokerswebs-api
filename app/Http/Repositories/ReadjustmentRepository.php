<?php

namespace App\Http\Repositories;

use App\Models\Base\Readjustment;
use App\Traits\ApiResponse;
use Illuminate\Config\Repository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ReadjustmentRepository extends Repository
{
    use ApiResponse;

    private $model;

    public function __construct()
    {
        $this->model = new Readjustment();
    }

    public function all()
    {
        $readjustments = $this->model->all();
        return $this->successResponse($readjustments);
    }

    public function create($request)
    {
        $valid = Validator::make($request->all(), [
            'residential_unit' => 'required|string',
            'apt_no' => 'required|numeric',
            'tenant_name' => 'required|string',
            'date_visit' => 'required',
            'worth' => 'required',
            'start_contract' => 'required|date',
            'end_contract' => 'required|date|after:start_contract',
            'phone' => 'required|max:20',
            'owner_name' => 'required|string',
            'phone_two' => 'required|max:20',
            'readjustment' => 'required|date',
            'status' => 'required'
        ]);

        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        $worth = str_replace(',', '', $request->worth);
        $request->merge([
            'worth' => $worth
        ]);

        try {
            $readjustment = $this->model->create($request->all());
            return $this->successResponse($readjustment, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error mientras se creaba el reajuste', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function update($request, $id)
    {
        $readjustment = $this->model->find($id);

        if (!$readjustment) {
            return $this->errorResponse('No se encontró el reajuste', Response::HTTP_NOT_FOUND);
        }
        $valid = Validator::make($request->all(), [
            'residential_unit' => 'required|string',
            'apt_no' => 'required|numeric',
            'tenant_name' => 'required|string',
            'date_visit' => 'required',
            'worth' => 'required',
            'start_contract' => 'required|date',
            'end_contract' => 'required|date|after:start_contract',
            'phone' => 'required|max:20',
            'owner_name' => 'required|string',
            'phone_two' => 'required|max:20',
            'readjustment' => 'required|date',
            'status' => 'required'
        ]);

        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        $worth = str_replace(',', '', $request->worth);
        $request->merge([
            'worth' => $worth
        ]);

        try {
            $readjustment->update($request->all());
            return $this->successResponseWithMessage('Reajuste actualizado exitosamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error mientras se actualizaba el reajuste', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getReadjustment($id)
    {
        $readjustment = $this->model->find($id);

        if (!$readjustment) {
            return $this->errorResponse('No se encontró el reajuste', Response::HTTP_NOT_FOUND);
        }

        return $this->successResponse($readjustment);
    }
    public function delete($id)
    {
        $readjustment = $this->model->find($id);

        if (!$readjustment) {
            return $this->errorResponse('No se encontró el reajuste', Response::HTTP_NOT_FOUND);
        }

        try {
            $readjustment->delete();
            return $this->successResponseWithMessage('Reajuste eliminado exitosamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error mientras se eliminaba el reajuste', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
