<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Resources\Inventory\TooloanResource;
use App\Models\Inventory\Tool;
use App\Models\Inventory\ToolLoan;
use App\Models\Inventory\ToolLoanDetail;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Barryvdh\DomPDF\Facade\Pdf as PDF;

class ToolLoanController extends Controller
{
    public function index()
    {
        $tools = TooloanResource::collection(ToolLoan::orderBy('created_at', 'desc')->get());

        return $this->successResponse($tools);
    }

    public function store(Request $request)
    {
        $valid = Validator::make($request->all(), [
            'assigned_id' => 'required',
            'expected_return_date' => 'required',
            'comment' => 'nullable|max:5000',
            'details' => 'required|array',
        ]);

        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }


        // Validate if the tools are available
        $tools = Tool::whereIn('id', array_column($request->details, 'tool_id'))->get();

        $errors = [];

        foreach ($tools as $tool) {
            $toolDetail = collect($request->details)->where('tool_id', $tool->id)->first();
            if ($tool->available_quantity < $toolDetail['qty']) {
                $errors[] = "La herramienta {$tool->name} no se puede prestar porque solo hay {$tool->available_quantity} cantidad disponible";
            }
        }

        // Si hay errores, los retornamos
        if (!empty($errors)) {
            return $this->errorResponse([
                'message' => 'No se pueden prestar las siguientes herramientas:',
                'code' => 401,
                'errors' => $errors
            ], Response::HTTP_BAD_REQUEST);
        }

        $tool = ToolLoan::create([
            'user_id' => Auth::id(),
            'assigned_id' => $request->assigned_id,
            'loan_date' => Carbon::now(),
            'expected_return_date' => $request->expected_return_date,
            'notes' => $request->comment,
        ]);

        $tool->details()->createMany($request->details);

        // Descontamos las herramientas prestadas
        foreach ($tools as $tool) {
            $toolDetail = collect($request->details)->where('tool_id', $tool->id)->first();
            $tool->update([
                'available_quantity' => $tool->available_quantity - $toolDetail['qty'],
            ]);

            // Si la cantidad disponible es 0, cambiamos el estado a no disponible
            if ($tool->available_quantity == 0) {
                $tool->update([
                    'status' => 'out_stock',
                ]);
            }
        }

        return $this->successResponseWithMessage('Herramienta prestada exitosamente', Response::HTTP_CREATED);
    }

    public function show($id)
    {
        try {
            $tool = ToolLoan::find($id)->load('details');
            return $this->successResponse($tool);
        } catch (\Throwable $th) {
            return $this->errorResponse('Prestamo no encontrado', Response::HTTP_NOT_FOUND);
        }
    }

    public function update(Request $request, $id)
    {
        $valid = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'assigned_id' => 'required',
            'expected_return_date' => 'required',
            'notes' => 'nullable|500',
            'details' => 'required|array',
        ]);

        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        $tool = ToolLoan::find($id);

        $tool->update([
            'user_id' => $request->user_id,
            'assigned_id' => $request->assigned_id,
            'expected_return_date' => $request->expected_return_date,
            'notes' => $request->notes,
        ]);

        $tool->details()->delete();
        $tool->details()->createMany($request->details);

        return $this->successResponseWithMessage('Herramienta actualizada exitosamente', Response::HTTP_OK);
    }

    public function destroy($id)
    {
        $tool = ToolLoan::find($id);
        $tool->delete();
        return $this->successResponseWithMessage('Herramienta eliminada exitosamente', Response::HTTP_OK);
    }


    // TODO:: ITEMS DETALLE
    // Add Itmes
    public function addItems(Request $request, $id)
    {
        $valid = Validator::make($request->all(), [
            'details' => 'required|array',
        ]);

        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        $tool = ToolLoan::find($id);

        $tool->details()->createMany($request->details);

        return $this->successResponseWithMessage('Herramientas agregadas exitosamente', Response::HTTP_OK);
    }

    // Delete an Item
    public function removeItem($id)
    {
        $tool = ToolLoanDetail::find($id);
        if (!$tool) {
            return $this->errorResponse('Herramienta no encontrada', Response::HTTP_NOT_FOUND);
        }
        $tool->delete();
        return $this->successResponseWithMessage('Herramienta eliminada exitosamente', Response::HTTP_OK);
    }

    public function getItem($id)
    {
        $tool = ToolLoanDetail::find($id);
        if (!$tool) {
            return $this->errorResponse('Herramienta no encontrada', Response::HTTP_NOT_FOUND);
        }
        return $this->successResponse($tool);
    }

    // Update an Item
    public function updateItemQty(Request $request, $id)
    {
        $valid = Validator::make($request->all(), [
            'qty' => 'required|integer',
        ]);

        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        $tool = ToolLoanDetail::find($id);

        if (!$tool) {
            return $this->errorResponse('Herramienta no encontrada', Response::HTTP_NOT_FOUND);
        }

        $tool->update([
            'qty' => $request->qty,
        ]);

        return $this->successResponseWithMessage('Herramienta actualizada exitosamente', Response::HTTP_OK);
    }



    public function tools()
    {
        $tools = Tool::select('id', 'name', 'code')
            ->where('available_quantity', '>', 0)
            ->orderBy('created_at', 'desc')->get();
        return $this->successResponse($tools);
    }


    public function getToolsLoanTool($id)
    {
        $tloan = ToolLoan::find($id)->load('details');
        if (!$tloan) {
            return $this->errorResponse('Prestamo no encontrado', Response::HTTP_NOT_FOUND);
        }

        try {

            return $this->successResponse([
                'id' => $tloan->id,
                'code' => $tloan->code,
                'assigned_id' => $tloan->assigned_id,
                'loan_date' => $tloan->loan_date,
                'expected_return_date' => $tloan->expected_return_date,
                'notes' =>  $tloan->notes,
                'tools' => $tloan->details,
            ], Response::HTTP_OK);
        } catch (\Throwable $th) {
            return $this->errorResponse(
                'Error al obtener el prestamo',
                Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }



    public function generatePDFDetails($id)
    {
        $toolLoan = ToolLoan::find($id);

        if (!$toolLoan) {
            return $this->errorResponse('Prestamo no encontrado', Response::HTTP_NOT_FOUND);
        }

        try {

            $data = [
                'created_at' => Carbon::now()->format('Y-m-d'),
                'code' =>  $toolLoan->code,
            ];

            $pdf = PDF::loadView('inventory.tool_loan_details', $data);
            $pdf->setPaper('a4', 'portrait');
            $pdfContent = $pdf->output();
            $timestamp = now()->timestamp;
            $fileName = 'Prestame de herramientas_' . $data['code'] . '_' . $timestamp;
        } catch (\Exception $e) {
            return $this->errorResponse('Ocurrió un error mientras se creaba el estado de cuenta', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
