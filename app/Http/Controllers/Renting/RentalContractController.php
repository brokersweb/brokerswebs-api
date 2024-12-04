<?php

namespace App\Http\Controllers\Renting;

use App\Http\Controllers\Controller;
use App\Models\Contracts\RentalContract;
use App\Models\Immovable;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class RentalContractController extends Controller
{

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'immovable_id' => 'required|exists:immovables,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'cutoff_day' => 'required|integer',
            'cosigner_id' => 'required|exists:cosigners,id',
            'cosignerii_id' => 'nullable',
            'reference_id' => 'nullable',
            'referenceii_id' => 'nullable',
        ]);


        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], Response::HTTP_BAD_REQUEST);
        }

        $immovable = Immovable::find($request->immovable_id);

        if (!$immovable) {
            return $this->errorResponse('Inmueble no encontrado', Response::HTTP_NOT_FOUND);
        }

        try {

            $contract = RentalContract::create([
                'immovable_id' => $immovable->id,
                'rent_price' => $immovable->rent_price,
                'tenant_id' => $immovable->tenant_id,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
                'cutoff_day' => $request->cutoff_day,
                'cosigner_id' => $request->cosigner_id,
                'cosignerii_id' => $request->cosignerii_id,
                'reference_id' => $request->reference_id,
                'referenceii_id' => $request->referenceii_id,
            ]);

            return $this->successResponseWithMessage(
                "El contrato de arrendamiento se ha generado exitosamente. " .
                    "El número de contrato asignado es: {$contract->rentalnum}. " .
                    "Por favor, guarde este número para futuras referencias."
            );
        } catch (\Throwable $th) {
            return $this->errorResponse('Ha ocurrido un error al generar el contrato', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
