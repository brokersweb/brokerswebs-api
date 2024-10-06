<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Controllers\Controller;
use App\Http\Resources\Inventory\Material\OperationMaterialResource;
use App\Models\Inventory\InventoryStockMaterial;
use App\Models\Inventory\Material;
use App\Models\Inventory\OperationMaterial;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use PhpParser\Node\Stmt\TryCatch;

class InventoryOperationMaterialController extends Controller
{

    // TODO:: DEVOLUCION Y ENTREGA DE MATERIALES
    public function store(Request $request)
    {

        $valid = Validator::make($request->all(), [
            'operation_type' => 'required',
            'staff_id' => 'required',
            'observation' => 'required',
            'details' => 'required',
        ]);

        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        DB::beginTransaction();

        try {
            $operationType = (int) $request->operation_type;
            $details = collect($request->details);

            $method = $operationType === 1 ? 'handleAddition' : 'handleSubtraction';

            $result = $this->$method($details, $request->staff_id, $request->all());

            if (isset($result['errors'])) {
                return $this->errorResponse($result, Response::HTTP_BAD_REQUEST);
            }

            DB::commit();
            return $this->successResponseWithMessage('Operación realizada exitosamente', Response::HTTP_CREATED);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse('Error al realizar la operación: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    // TODO:: ENTREGA
    private function handleAddition($details, $staffId, $request)
    {
        $materials = Material::whereIn('id', $details->pluck('material_id'))->get();

        $errors = $this->checkStockAvailability($materials, $details);
        if (!empty($errors)) {
            return $this->formatErrors($errors);
        }

        foreach ($details as $detail) {
            $this->updateOrCreateInventoryStock($detail, $staffId);
            $this->updateMaterialStock($detail['material_id'], -$detail['qty']);
        }

        $op = OperationMaterial::create([
            'user_id' => Auth::id(),
            'info' => json_encode($request)
        ]);

        return [];
    }
    //TODO:: DEVOLUCION
    private function handleSubtraction($details, $staffId, $request)
    {
        $inventoryStocks = InventoryStockMaterial::whereIn('material_id', $details->pluck('material_id'))
            ->where('owner_id', $staffId)
            ->get();

        $errors = $this->checkInventoryAvailability($inventoryStocks, $details);
        if (!empty($errors)) {
            return $this->formatErrors($errors);
        }

        foreach ($details as $detail) {
            $this->updateInventoryStock($detail, $staffId);
            $this->updateMaterialStock($detail['material_id'], $detail['qty']);
        }


        $op = OperationMaterial::create([
            'user_id' => Auth::id(),
            'info' => json_encode($request)
        ]);

        return [];
    }

    private function checkStockAvailability($materials, $details)
    {
        return $materials->reduce(function ($errors, $material) use ($details) {
            $detail = $details->firstWhere('material_id', $material->id);
            if ($material->stock < $detail['qty']) {
                $errors[] = "Del material {$material->name} solo hay {$material->stock} disponible";
            }
            return $errors;
        }, []);
    }

    private function checkInventoryAvailability($inventoryStocks, $details)
    {
        return $inventoryStocks->reduce(function ($errors, $stock) use ($details) {
            $detail = $details->firstWhere('material_id', $stock->material_id);
            if ($stock->qty < $detail['qty']) {
                $errors[] = "Del material {$stock->material->name} solo hay {$stock->qty} disponible";
            }
            return $errors;
        }, []);
    }

    private function formatErrors($errors)
    {
        return [
            'message' => 'Cantidades o Stock Insuficiente para los siguientes materiales:',
            'code' => 401,
            'errors' => $errors
        ];
    }

    private function updateOrCreateInventoryStock($detail, $staffId)
    {
        InventoryStockMaterial::updateOrCreate(
            ['material_id' => $detail['material_id'], 'owner_id' => $staffId],
            ['qty' => DB::raw('qty + ' . $detail['qty']), 'user_id' => Auth::id()]
        );
    }

    private function updateInventoryStock($detail, $staffId)
    {
        InventoryStockMaterial::where('material_id', $detail['material_id'])
            ->where('owner_id', $staffId)
            ->decrement('qty', $detail['qty']);
    }

    private function updateMaterialStock($materialId, $qtyChange)
    {
        $material = Material::find($materialId);
        $material->stock = max(0, $material->stock + $qtyChange);
        $material->status = $material->stock > 0 ? 'available' : 'out_stock';
        $material->save();
    }


    public function index()
    {

        try {
            $all = OperationMaterialResource::collection(OperationMaterial::all());
            return $this->successResponse($all, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al realizar la operación: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function show($code)
    {
        try {
            $op = OperationMaterial::where('code', $code)->first();
            if (!$op) {
                return  $this->errorResponse('Operación no encontrada', Response::HTTP_NOT_FOUND);
            }
            $all = OperationMaterialResource::collection(OperationMaterial::where('code', $code)->get());
            return $this->successResponse($all[0], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->errorResponse('Error al realizar la operación: ' . $e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
