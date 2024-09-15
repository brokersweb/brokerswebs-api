<?php

namespace App\Http\Controllers\Inventory;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Utils\UtilsController;
use App\Http\Resources\Inventory\PurchaseResource;
use App\Models\Inventory\InventoryPurchase;
use App\Models\Inventory\InventoryPurchaseDetail;
use App\Models\Inventory\Material;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;

class InventoryPurchaseController extends Controller
{

    public function index()
    {
        $entrances = PurchaseResource::collection(InventoryPurchase::orderBy('created_at', 'desc')->get());
        return $this->successResponse($entrances);
    }

    public function store(Request $request)
    {

        $valid = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'user_approved_id' => 'nullable',
            'supplier_id' => 'required',
            'observation' => 'nullable',
            'invoice' => 'nullable',
            'total' => 'required',
            'status' => 'required',
            'details' => 'required|array',
        ]);

        if ($valid->fails()) {
            return $this->errorResponse($valid->errors(), Response::HTTP_BAD_REQUEST);
        }

        $purchase = InventoryPurchase::create([
            'user_id' => $request->user_id,
            'user_approved_id' => $request->user_approved_id,
            'supplier_id' => $request->supplier_id,
            'observation' => $request->observation,
            'invoice' => $request->invoice,
            'total' => $request->total,
            'status' => $request->status,
            'serial' => (new UtilsController())->generateSerialNumber(),
        ]);

        // Details
        foreach ($request->details as $detail) {
            $purchase->details()->create([
                // 'inventory_purchase_id' => $purchase->id,
                'material_id' => $detail['id'],
                'material_type' => Material::class,
                'qty' => $detail['qty'],
                'price' => $detail['price'],
                'total' => $detail['price'] * $detail['qty'],
            ]);
        }

        return $this->successResponse($purchase, Response::HTTP_CREATED);
    }
}
