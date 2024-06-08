<?php

namespace App\Http\Repositories;

use App\Models\Immovable;
use App\Models\ImmovableRequest;
use App\Traits\ApiResponse;
use Illuminate\Config\Repository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ImmovableRequestRepository extends Repository
{
    use ApiResponse;
    private $model;

    public function __construct()
    {
        $this->model = new ImmovableRequest();
    }

    public function all()
    {
        $immovableRequests = $this->model->all();
        return $this->successResponse($immovableRequests);
    }

    public function create($request)
    {
        $valid = Validator::make($request->all(), [
            'fullname' => 'required|string|max:50',
            'email' => 'nullable|email',
            'message' => 'nullable|string|max:300',
            'phone' => 'required|max:20',
            'type' => 'required|in:visit,financiation',
            'code' => 'required|exists:immovables,code',
            'terms' => 'required'
        ]);
        $immovable = Immovable::where('code', $request->code);
        if ($valid->fails()) {
            return $this->errorResponseBadRequest($valid->errors());
        }
        try {
            $request->merge([
                'immovable_id' => $immovable->first()->id,
            ]);
            $immovableRequest = $this->model->create($request->all());
            return $this->successResponse($immovableRequest, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error mientras se creaba la solicitud', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getRequest($id)
    {
        $immovableRequest = $this->model->find($id);
        if (!$immovableRequest) {
            return $this->errorResponse('No se encontró la solicitud', Response::HTTP_NOT_FOUND);
        }
        try {
            return $this->successResponse($immovableRequest->load('immovable.details'));
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error mientras se obtenía la solicitud', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
