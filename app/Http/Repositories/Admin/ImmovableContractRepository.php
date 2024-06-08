<?php

namespace App\Http\Repositories\Admin;

use App\Models\Contracts\AdminContractRequest;
use App\Traits\ApiResponse;
use Illuminate\Config\Repository;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class ImmovableContractRepository extends Repository
{
    use ApiResponse;
    private $madminrequest;

    public function __construct()
    {
        $this->madminrequest = new AdminContractRequest();
    }

    // Save file to admin contract request
    public function storeRequest($request)
    {
        $valid = Validator::make($request->all(), [
            'immovable_id' => 'required|exists:immovables,id|unique:admincontract_requirements,immovable_id',
            'dnis' => 'required',
            'certificate' => 'required',
            'utilitybills' => 'required',
            'invoice' => 'required',
        ]);
        if ($valid->fails()) {
            return $this->errorResponseBadRequest($valid->errors());
        }
        try {
            $this->madminrequest::create([
                'immovable_id' => $request->immovable_id,
                'owner_dni' => json_encode($request->dnis),
                'certificate' => json_encode($request->certificate),
                'utility_bills' => json_encode($request->utilitybills),
                'invoice' => json_encode($request->invoice),
            ]);
            return $this->successResponseWithMessage('Documentos guardados exitosamente');
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error mientras se guardaban los documentos', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
