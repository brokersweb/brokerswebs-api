<?php

namespace App\Http\Repositories\Admin;

use App\Models\Base\LeaseDocContract;
use App\Models\Immovable;
use App\Traits\ApiResponse;
use Illuminate\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class LeaseDocContractRepository extends Repository
{
    use ApiResponse;
    private $model;

    public function __construct()
    {
        $this->model = new LeaseDocContract();
    }

    public function index()
    {
        $leases = $this->model::get();
        return $this->successResponse($leases, Response::HTTP_OK);
    }

    public function create(Request $request)
    {
        $rulesData = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'tenant_id' => 'required|exists:tenants,id',
            'immovable_id' => 'required|exists:immovables,id',
            'file_path' => 'required',
        ]);
        if ($rulesData->fails()) {
            return $this->errorResponse($rulesData->errors(), Response::HTTP_BAD_REQUEST);
        }

        $immovable = Immovable::find($request->immovable_id);
        $tenant = $immovable->tenants()->where('tenant_id', $request->tenant_id)->first();

        // $cosigner_tenant = $tenant->pivot->cosigner;
        // $cosigners = json_encode([
        //     'name' => $request->cosigner_name,
        //     'dni' => $request->cosigner_dni,
        //     'expedition_place' => $request->cosigner_expedition_place,
        // ]);

        // dd($tenant);

        try {
            $this->model::create([
                'user_id' => $request->user_id,
                'tenant_id' => $request->tenant_id,
                'immovable_id' => $request->immovable_id,
                'document_path' => $request->file_path,
                'tenant_name' => $tenant->name . ' ' . $tenant->last_name,
                'tenant_dni' => $tenant->dni,
                'tenant_dni_expedition' => $tenant->expedition_date,
                'tenant_dni_expedition_place' => $tenant->expedition_place,
                'immovable_address' => $immovable->address->city . ',' . $immovable->address->municipality . ' ' . $immovable->co_ownership_name,
                'immovable_rent_price' => $immovable->rent_price,
                'immovable_duration_rent' => 2,
                'immovable_start_contract' => 3,
                'immovable_end_contract' => 2,
                'immovable_nmonths_contract' => 1,
                'cosigner' => null,
            ]);
            return $this->successResponseWithMessage('Contrato creado correctamente', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->errorResponse('Ha ocurrido un error al crear el contrato', Response::HTTP_BAD_REQUEST);
        }
    }
}
