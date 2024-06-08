<?php

namespace App\Http\Repositories\Renting;


use App\Interfaces\ReferenceInterface;
use App\Models\Renting\Reference;
use App\Traits\ApiResponse;
use Illuminate\Config\Repository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ReferenceRepository extends Repository implements ReferenceInterface
{

    private $model;
    use ApiResponse;

    public function __construct()
    {
        $this->model = new Reference();
    }

    public function references()
    {
        return $this->successResponse($this->model->all());
    }

    public function reference($id)
    {
        try {
            $reference = $this->model->findOrFail($id)->load('applicant');
            return $this->successResponse($reference);
        } catch (\Throwable $th) {
            return $this->errorResponse('Reference not found', 404);
        }
    }

    public function create($request)
    {
        $rules = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'residence_address' => 'required|string',
            'residence_country' => 'required|string',
            'residence_department' => 'required|string',
            'residence_city' => 'required|string',
            'kinship' => 'required|string',
            'type' => 'required|string|in:Familiar,Personal',
            'phone' => 'required|string|max:25',
            'applicant_id' => 'required|string|exists:applicants,id'
        ]);
        if ($rules->fails()) {
            return $this->errorResponse($rules->errors(), Response::HTTP_BAD_REQUEST);
        }
        try {
            $reference = $this->model->create($request->all());
            return $this->successResponse($reference, Response::HTTP_CREATED);
        } catch (\Throwable $th) {
            return $this->errorResponse('Reference not created', Response::HTTP_BAD_REQUEST);
        }
    }

    public function update($request, $id)
    {
        $rules = Validator::make($request->all(), [
            'name' => 'string|max:255',
            'lastname' => 'string|max:255',
            'residence_address' => 'string',
            'residence_country' => 'string',
            'residence_department' => 'string',
            'residence_city' => 'string',
            'kinship' => 'string',
            'type' => 'string|in:Familiar,Personal',
            'phone' => 'string|max:25',
            'applicant_id' => 'string|exists:applicants,id'
        ]);
        if ($rules->fails()) {
            return $this->errorResponse($rules->errors(), Response::HTTP_BAD_REQUEST);
        }
        try {
            $reference = $this->model->findOrFail($id);
            $reference->update($request->all());
            return $this->successResponse($reference, Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse('Reference not updated', Response::HTTP_BAD_REQUEST);
        }
    }

    public function delete($id)
    {
        try {
            $reference = $this->model->findOrFail($id);
            $reference->delete();
            return $this->successResponse('Reference deleted', Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse('Reference not deleted', Response::HTTP_BAD_REQUEST);
        }
    }
}
