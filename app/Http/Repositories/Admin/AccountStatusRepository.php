<?php

namespace App\Http\Repositories\Admin;

use App\Http\Controllers\Utils\UtilsController;
use App\Http\Resources\Admin\AccountStatusResource;
use App\Http\Resources\AccountStatusPdfResource;
use App\Http\Resources\Admin\AccountStatusSelectResource;
use App\Models\AccountsStatus\AccountStatus;
use App\Models\AccountsStatus\AccountStatusDetail;
use App\Models\AccountsStatus\PreviousBalance;
use App\Models\Immovable;
use App\Traits\ApiResponse;
use Illuminate\Config\Repository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class AccountStatusRepository extends Repository
{
    use ApiResponse;
    private $model;
    private UtilsController $utilsController;

    public function __construct()
    {
        $this->model = new AccountStatus();
        $this->utilsController = new UtilsController();
    }

    public function all()
    {
        $accountStatus = AccountStatusResource::collection($this->model->all());
        return $this->successResponse($accountStatus);
    }

    public function getAccountStatus($id)
    {
        $account = $this->model::find($id)->load('details');
        try {
            if (!empty($account)) {
                return response()->json($account);
            }
            return $this->errorResponse('No se encontró el estado de cuenta', Response::HTTP_NOT_FOUND);
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error mientras se obtenía el detalle o vista previa', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function getOwnerAccountStatus($owner_id)
    {
        $accountStatus = AccountStatusResource::collection($this->model->where('owner_id', $owner_id)->get());
        try {
            if (count($accountStatus) > 0) {
                return $this->successResponse($accountStatus);
            } else {
                return $this->errorResponse('No se encontraron estados de cuenta para el propietario', Response::HTTP_NOT_FOUND);
            }
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error mientras se obtenían los estados de cuenta del propietario', Response::HTTP_INTERNAL_SERVER_ERROR);
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
            'balance' => 'required'
        ]);
        if ($valid->fails()) {
            return $this->errorResponseBadRequest($valid->errors());
        }
        $immovable = Immovable::find($request->immovable_id);
        $details = $request->details;
        $existatus_account = AccountStatus::where('immovable_id', $immovable->id)->first();
        try {
            $accountStatus = $this->model->create([
                'immovable_id' => $request->immovable_id,
                'owner_id' => $immovable->owner_id,
                'contract_number' => '1010',
                'month' => $month,
                'year' => $year
            ]);

            for ($i = 0; $i < count($details); $i++) {
                AccountStatusDetail::create([
                    'accountstatus_id' => $accountStatus->id,
                    'qty' => $details[$i]['qty'],
                    'concept' => $details[$i]['concept'],
                    'value_unit' => $details[$i]['value_unit'],
                    'amount' => $details[$i]['amount'],
                    'immovable_code' => $immovable->code,
                    'owner_dni' => $immovable->owner->dni,
                    'cutoff_date' => '2021-01-01'
                ]);
            }
            if ($existatus_account !== null) {
                $this->updatePreviewBalance($request, $accountStatus);
            } else {
                $this->createPreviewBalance($request, $accountStatus);
            }
            return $this->successResponse($accountStatus, Response::HTTP_CREATED);
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error mientras se creaba el estado de cuenta', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function updatePreviewBalance($request, $account)
    {
        $latest = AccountStatus::where('immovable_id', $request->immovable_id)->orderBy('created_at', 'desc')                   // Ordenar en orden descendente por ID
            ->skip(1)
            ->take(1)
            ->first();
        $previous_balance = PreviousBalance::where('account_status_id', $latest->id)->first();

        if (!empty($previous_balance)) {
            if ((!empty($latest)) && ($request->balance < 0)) {
                $preview = $previous_balance->balance >= 0 ? $previous_balance->balance : $previous_balance->balance * (-1);
                $previous_balance->update([
                    'account_status_id' => $account->id,
                    'balance' => $preview + $request->balance * -1,
                ]);
            } else if ((!empty($latest)) && ($request->balance >= 0)) {
                $previous_balance->update([
                    'account_status_id' => $account->id,
                    'balance' => 0,
                ]);
            }
        }
    }
    public function createPreviewBalance($request, $account)
    {
        PreviousBalance::create([
            'account_status_id' => $account->id,
            'balance' => $request->balance <= 0 ? $request->balance : 0,
        ]);
    }

    public function getAccountStatusPdfData($id)
    {
        $accountStatu = AccountStatusPdfResource::collection($this->model->where('id', $id)->get());
        return response()->json($accountStatu[0], Response::HTTP_OK);
    }
    // Select Formulario
    public function getAccountStatusSelect(): JsonResponse
    {
        $immovables = AccountStatusSelectResource::collection(Immovable::where('category', '!=', 'sale')->get());
        foreach ($immovables as $immovable) {
            $account = AccountStatus::where('immovable_id', $immovable->id)->orderBy('id', 'desc')->first();
            if ($account) {
                $balance = PreviousBalance::where('account_status_id', $account->id)->first();
                if ($balance) {
                    $immovable->balance = $balance->balance;
                } else {
                    $immovable->balance = 0;
                }
            } else {
                $immovable->balance = 0;
            }
        }
        return response()->json($immovables);
    }
}
