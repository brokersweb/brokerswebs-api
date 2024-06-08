<?php

namespace App\Http\Repositories\Admin;

use App\Http\Controllers\Utils\UtilsController;
use App\Http\Resources\Admin\Billing\AccountCollectionResource;
use App\Http\Resources\Admin\Billing\AccountCollectionPdfResource;
use App\Http\Resources\Admin\Billing\AccountCollectionSelectResource;
use App\Http\Resources\Admin\Billing\CollectionDetailsResource;
use App\Models\AccountsCollection\AccountCollection;
use App\Models\AccountsCollection\AccountCollectionDetail;
use App\Models\Immovable;
use App\Traits\ApiResponse;
use Illuminate\Config\Repository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class AccountCollectionRepository extends Repository
{
    use ApiResponse;
    private $model;
    private UtilsController $utilsController;

    public function __construct()
    {
        $this->model = new AccountCollection();
        $this->utilsController = new UtilsController();
    }

    public function all()
    {
        $accountCollection = AccountCollectionResource::collection($this->model->all());
        return $this->successResponse($accountCollection);
    }
    public function getAccountCollection($id)
    {
        $account = $this->model::find($id)->load('details');

        try {
            if (!empty($account)) {
                return response()->json($account);
            }
            return $this->errorResponse('No se encontró la cuenta de cobro', Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error mientras se obtenía el detalle o vista previa', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getTenantAccountsCollection($tenant_id)
    {
        $accountCollection = AccountCollectionResource::collection($this->model->where('tenant_id', $tenant_id)->get());
        try {
            if (count($accountCollection) > 0) {
                return $this->successResponse($accountCollection);
            } else {
                return $this->errorResponse('No se encontraron cuentas de cobro para el inquilino', Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error mientras se obtenían los cuentas de cobro del inquilino', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function create($request)
    {
        $date_now = date('Y-m-d');
        $month = date('F', strtotime($date_now));
        $year = date('Y', strtotime($date_now));
        $month = $this->utilsController->converMonthInSpanish($month);

        $valid = Validator::make($request->all(), [
            'immovable_id' => 'required|exists:immovables,id',
            'details' => 'required',
        ]);
        if ($valid->fails()) {
            return $this->errorResponseBadRequest($valid->errors());
        }
        $immovable = Immovable::find($request->immovable_id);
        // get tenant where type is holder
        $tenant =  $immovable->tenants()->where('type', 'holder')->first();
        $details = $request->details;
        try {
            if ($tenant) {
                $accountCollection = $this->model->create([
                    'immovable_id' => $request->immovable_id,
                    'tenant_id' => 1,
                    'contract_number' => '1001',
                    'month' => $month,
                    'year' => $year
                ]);
                for ($i = 0; $i < count($details); $i++) {
                    AccountCollectionDetail::create([
                        'accountscollection_id' => $accountCollection->id,
                        'qty' => $details[$i]['qty'],
                        'concept' => $details[$i]['concept'],
                        'value_unit' => $details[$i]['value_unit'],
                        'amount' => $details[$i]['amount'],
                        'immovable_code' => $immovable->code,
                        'tenant_dni' => $tenant->dni,
                        'cutoff_date' => '2021-01-01'
                    ]);
                }
                return $this->successResponse($accountCollection, Response::HTTP_CREATED);
            } else {
                return $this->errorResponse('No se encontró el inquilino', Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error mientras se creaba la cuenta de cobro', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getAccountCollectionPdfData($id)
    {
        $accountStatu = AccountCollectionPdfResource::collection($this->model->where('id', $id)->get());
        if (count($accountStatu) > 0) {
            return response()->json($accountStatu[0], Response::HTTP_OK);
        } else {
            return $this->errorResponse('No se encontró el estado de cuenta', Response::HTTP_NOT_FOUND);
        }
    }

    // Select formulario de generar cuenta de cobro
    public function getAccountCollectionSelect(): JsonResponse
    {
        $immovables = AccountCollectionSelectResource::collection(Immovable::where('category', 'rent')->whereHas('tenants')->get());
        return response()->json($immovables);
    }
}
