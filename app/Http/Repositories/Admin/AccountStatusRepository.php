<?php

namespace App\Http\Repositories\Admin;

use App\Helpers\NumberToWords;
use App\Http\Controllers\Utils\UtilsController;
use App\Http\Resources\Admin\AccountStatusResource;
use App\Http\Resources\AccountStatusPdfResource;
use App\Http\Resources\Admin\AccountStatusSelectResource;
use App\Models\AccountsStatus\AccountStatus;
use App\Models\AccountsStatus\AccountStatusDetail;
use App\Models\AccountsStatus\PreviousBalance;
use App\Models\CompanyConfiguration;
use App\Models\Immovable;
use App\Models\Invoice\ConfigurationInvoice;
use App\Models\Invoice\Invoice;
use App\Traits\ApiResponse;
use BaconQrCode\Encoder\QrCode;
use Carbon\Carbon;
use Illuminate\Config\Repository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Number;
use Barryvdh\DomPDF\Facade\Pdf as PDF;
use SimpleSoftwareIO\QrCode\Facades\QrCode as FacadesQrCode;

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
            'balance' => 'required',
            'payment_condition' => 'required',
            'nquota' => 'required_if:payment_condition,3',
            'observation' => 'nullable'
        ]);
        if ($valid->fails()) {
            return $this->errorResponseBadRequest($valid->errors());
        }

        $immovable = Immovable::find($request->immovable_id);
        $existatus_account = AccountStatus::where('immovable_id', $immovable->id)->first();

        // Configure Invoice
        $configInvoice = ConfigurationInvoice::first();

        DB::beginTransaction();

        // try {
            $accountStatus = $this->model->create([
                'immovable_id' => $request->immovable_id,
                'owner_id' => $immovable->owner_id,
                'contract_number' => '1010',
                'month' => $month,
                'year' => $year,
                'expiration_date' => '2025-09-01',
                'terms_payment' => $this->getPaymentCondition($request->payment_condition),
                'observation' => $request->observation
            ]);
            // Crear detalles y calcular totales
            $totalAmount = 0;
            foreach ($request->details as $detail) {
                $accountStatus->details()->create([
                    'qty' => $detail['qty'],
                    'concept' => $detail['concept'],
                    'value_unit' => $detail['value_unit'],
                    'amount' => $detail['amount'],
                    'immovable_code' => $immovable->code,
                    'owner_dni' => $immovable->owner->dni,
                ]);

                $totalAmount += $detail['amount'];
            }

            // Actualizar totales
            $amountVat = $totalAmount * ($configInvoice->vat / 100);
            $amountRetention = $totalAmount * ($configInvoice->retention / 100);
            $amountPaid = intval($totalAmount + $amountVat + $amountRetention);

            $accountStatus->update([
                'amount' => $totalAmount,
                'amount_vat' => $amountVat,
                'amount_retention' => $amountRetention,
                'amount_paid' => $amountPaid,
                'items' => count($request->details),
                'amount_in_letters' => NumberToWords::toPesos($amountPaid),
            ]);


            if ($existatus_account !== null) {
                $this->updatePreviewBalance($request, $accountStatus);
            } else {
                $this->createPreviewBalance($request, $accountStatus);
            }

            // Print PDF
            $this->generateInvoicePDF($request, $accountStatus, $configInvoice, $immovable);

            DB::commit();
            return $this->successResponseWithMessage('Estado de cuenta generado con éxito.', Response::HTTP_CREATED);
        // } catch (\Exception $e) {
            DB::rollBack();
        //     return $this->errorResponse('Ocurrió un error mientras se creaba el estado de cuenta', Response::HTTP_INTERNAL_SERVER_ERROR);
        // }
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

    public function getPaymentCondition($request)
    {
        $condition = '';

        switch ($request) {

            case 1:
                $condition = 'Transferencia';
                break;
            case 2:
                $condition = 'Consignación';
                break;

            case 3:
                $condition = 'Crédito';
                break;
        }

        return $condition;
    }

    function generateQuotes($total, $numeroCuotas, $fechaCreacion, $fechaVencimiento, $condicionPago)
    {
        if ($condicionPago != 3) {
            return [];
        }

        $cuotas = [];
        $valorCuota = $total / $numeroCuotas;
        $fechaCreacion = Carbon::parse($fechaCreacion);
        $fechaVencimiento = Carbon::parse($fechaVencimiento);

        if ($fechaVencimiento->lte($fechaCreacion)) {
            throw new \InvalidArgumentException("La fecha de vencimiento debe ser posterior a la fecha de creación.");
        }

        $mesesDisponibles = $fechaCreacion->diffInMonths($fechaVencimiento);

        // Si no hay suficientes meses para las cuotas, ajustamos el número de cuotas
        $numeroCuotas = min($numeroCuotas, $mesesDisponibles + 1);
        $valorCuota = $total / $numeroCuotas;

        for ($i = 1; $i <= $numeroCuotas; $i++) {
            $fechaCuota = $fechaCreacion->copy()->addMonths($i - 1);

            // Si la fecha de la cuota supera la fecha de vencimiento, usamos la fecha de vencimiento
            if ($fechaCuota->gt($fechaVencimiento)) {
                $fechaCuota = $fechaVencimiento->copy();
            }

            // Ajustamos el valor de la última cuota para que coincida con el total
            $valorCuotaActual = ($i == $numeroCuotas) ? ($total - ($valorCuota * ($numeroCuotas - 1))) : $valorCuota;

            $cuotas[] = [
                'description' => sprintf('Crédito - Cuota No. %03d vence %s', $i, $fechaCuota->format('Y-m-d')),
                'amount' => number_format($valorCuotaActual, 2, '.', ',')
            ];

            if ($fechaCuota->eq($fechaVencimiento)) {
                break;
            }
        }

        return $cuotas;
    }
    public function generateInvoicePDF($request, $account, $configInvoice, $immovable)
    {

        try {
            // Create Invoice
            $invoice = Invoice::create([
                'xml' => 1,
                'doc_file' => '1',
                'entityable_id' => $account->id,
                'entityable_type' => AccountStatus::class,
                'type' => 'account_status',
                'user_id' => Auth::id(),
            ]);

            $company = CompanyConfiguration::first();

            // QR Code
            $qrcode = base64_encode(FacadesQrCode::format('svg')->size(100)->errorCorrection('H')->generate('https://realestate-api.brokerssoluciones.com/api/invoice/download/' . $invoice->id));

            // Create quotes
            if ($request->payment_condition == 3) {
                $cuotas = $this->generateQuotes($account->amount_paid, $request->nquota, Carbon::now(), $account->expiration_date, $request->payment_condition);
                $account->update([
                    'payment_observation' => json_encode([
                        'nquota' => $request->nquota,
                        'quotes' => $cuotas
                    ]),
                ]);
            }

            $data = [
                'invoice_number' => $configInvoice->prefix . ' 405',
                'date' => Carbon::now()->format('Y-m-d'),
                'due_date' => Carbon::parse($account->expiration_date)->format('Y-m-d'),
                'customer' => [
                    'name' => $immovable->owner?->full_name,
                    'nit' => $immovable->owner?->nit,
                    'rut' => $immovable->owner?->rut,
                    'address' =>  $immovable->owner?->address,
                    'city' => $immovable->owner?->address,
                    'phone' => '(+57) ' . $immovable->owner?->cellphone,
                    'dni' => $immovable->owner?->dni,
                    'dniType' => $immovable->owner?->document_type,
                ],
                'company' => [
                    'name' => $company->name,
                    'email' => $company->email,
                    'nit' => $company->nit,
                    'address' => $company->address,
                    'city' => $company->city . ', ' . $company->department,
                    'phone' => '(+57) ' . $company->cellphone . ' - ' . $company->phone,
                ],
                'items' => $request->details,
                'total' => $account->amount,
                'total_paid' => $account->amount_paid,
                'total_in_letters' => $account->amount_in_letters,
                'payment_condition' => $account->terms_payment,
                'observations' => $request->observation,
                'payment_option' => $request->payment_condition,
                'quotes' => $cuotas ?? [],
                'iva' => [
                    'value' => $configInvoice->vat,
                    'total' => $account->amount_vat
                ],
                'retention' => [
                    'value' => $configInvoice->retention,
                    'total' => $account->amount_retention
                ],
                'config_invoice' => [
                    'prefix' => $configInvoice->prefix,
                    'cufe' => $configInvoice->cufe,
                    'vigence' => $configInvoice->validity,
                    'authorization_number' => $configInvoice->authorization_number,
                    'authorization_date' => $configInvoice->authorization_date,
                    'from' => $configInvoice->start_number,
                    'to' => $configInvoice->end_number,
                ],
                'qrcode' => $qrcode,
            ];

            $pdf = PDF::loadView('contables.invoice_account', $data);
            $pdf->setPaper('a4', 'portrait');
            $pdfContent = $pdf->output();
            $timestamp = now()->timestamp;
            $fileName = 'Estado de cuenta_' . $data['invoice_number'] . '_' . $timestamp . '.pdf';

            // Guardar el PDF en el storage
            $pdfPath = 'invoices/' . $data['customer']['dni'] . '/' . $fileName;
            // Storage::put($pdfPath, $pdf->output());

            $pdfBase64 = 'data:application/pdf;base64,' . base64_encode($pdfContent);

            // Save Doc
            $invoice->update([
                'doc_file' => $pdfBase64,
                'doc_name' => $fileName,
                'sequential' => $data['invoice_number'],
            ]);
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error mientras se creaba el estado de cuenta', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
