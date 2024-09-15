<?php

namespace App\Http\Repositories\Admin;

use App\Http\Resources\Admin\Tenant\ImmovableLetterResource;
use App\Http\Resources\Admin\Tenant\TenantLetterResource;
use App\Models\Immovable;
use App\Models\ImmovableTenant;
use App\Models\Renting\Cosigner;
use App\Models\Renting\Reference;
use App\Models\Tenant;
use App\Traits\ApiResponse;
use Illuminate\Config\Repository;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class TenantRepository extends Repository
{

    use ApiResponse;
    private $model;

    public function __construct()
    {
        $this->model = new Tenant();
    }


    public function index()
    {
        $tenants = $this->model->all();
        return $this->successResponse($tenants, Response::HTTP_OK);
    }

    public function create(Request $request)
    {
        $rulesData = Validator::make($request->all(), [
            'name' => 'required|max:255',
            'lastname' => 'required|max:255',
            'phone' => 'nullable|max:25',
            'cellphone' => 'required|max:25',
            'document_type' => 'required|in:CC,CE,TI,PPN,NIT,RC,RUT',
            'dni' => 'required|numeric|unique:tenants,dni',
            'dni_file' => 'nullable',
            'expedition_place' => 'required|max:100',
            'expedition_date' => 'required|date',
            'birthdate' => 'required|date',
            'gender' => 'required|in:Masculino,Femenino,Otro',
            'civil_status' => 'required|in:Soltero,Casado,Unión libre,Viudo,Divorciado',
            'dependent_people' => 'nullable|integer',
            'profession' => 'nullable',
            'email' => 'nullable|email',
            'address' => 'nullable|max:100',
            'immovable_id' => 'required|integer',
            'photo' => 'nullable',
            'bank_account' => 'nullable',
            'type' => 'required|in:holder,secondary',
        ]);
        if ($rulesData->fails()) {
            return $this->errorResponse($rulesData->errors(), Response::HTTP_BAD_REQUEST);
        }

        $tenant = $this->model->create($request->all());
        // Add the immovable
        ImmovableTenant::create([
            'immovable_id' => $request->immovable_id,
            'tenant_id' => $tenant->id,
        ]);
        return $this->successResponse($tenant, Response::HTTP_CREATED);
    }

    public function getImmovablesRenting($id)
    {
        // TenantLetterResource
        $tenant = TenantLetterResource::collection($this->model::whereId($id)->with('immovables')->get());
        try {
            if (!empty($tenant)) {
                return response()->json($tenant[0]);
            }
            return $this->errorResponse('No se encontró el inquilino', Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error mientras se obtenía el inquilino', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getImmovableById($id)
    {
        $immovable = ImmovableLetterResource::collection(Immovable::whereId($id)->with('address')->get());
        try {
            if (!empty($immovable)) {
                return $this->successResponse($immovable, Response::HTTP_OK);
            }
            return $this->errorResponse('No se encontró el inmueble', Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error mientras se obtenía el inmueble', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($id)
    {
        $tenant = Tenant::whereId($id)->with('references', 'cosigners')->get();

        try {
            if (!empty($tenant)) {
                return $this->successResponse($tenant[0], Response::HTTP_OK);
            }
            return $this->errorResponse('No se encontró el inquilino', Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error mientras se obtenía el inquilino', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getReferences($id)
    {
        $references = Reference::where('referencable_type', Tenant::class)->where('referencable_id', $id)->get();
        try {
            if (!empty($references)) {
                return $this->successResponse($references, Response::HTTP_OK);
            }
            return $this->errorResponse('No se encontró la referencia', Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error mientras se obtenía la referencia', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getCosigners($id)
    {
        $cosigners = Cosigner::where('cosignerable_type', Tenant::class)->where('cosignerable_id', $id)->get();
        try {
            if (!empty($cosigners)) {
                return $this->successResponse($cosigners, Response::HTTP_OK);
            }
            return $this->errorResponse('No se encontró el codeudor', Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error mientras se obtenía el codeudor', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
