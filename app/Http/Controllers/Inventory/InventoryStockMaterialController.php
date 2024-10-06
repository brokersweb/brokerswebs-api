<?php

namespace App\Http\Controllers\Inventory;

use App\Http\Resources\Inventory\MaterialStockOptionResource;
use App\Models\Inventory\InventoryStockMaterial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Inventory\Material\MaterialStockResource;

class InventoryStockMaterialController extends Controller
{
    //

    public function index()
    {
        $stocks = MaterialStockResource::collection(InventoryStockMaterial::whereHas('material', function ($query) {
            $query->where('stock', '>', 0);
        })->get());

        return $this->successResponse($stocks);
    }

    public function stockStaff($id)
    {
        $stocks = MaterialStockOptionResource::collection(InventoryStockMaterial::where('owner_id', $id)->where('qty', '>', 0)->get());
        return $this->successResponse($stocks);
    }
}
