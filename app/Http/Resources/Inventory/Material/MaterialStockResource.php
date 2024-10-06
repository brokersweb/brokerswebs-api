<?php

namespace App\Http\Resources\Inventory\Material;

use Illuminate\Http\Resources\Json\JsonResource;

class MaterialStockResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->material->id,
            'name' => $this->material->name,
            'staff' => $this->staff->name . ' ' . $this->staff->lastname,
            'code' => $this->material->code,
            'stock' => $this->material->stock,
            'updated_at' => $this->updated_at,
        ];
    }
}
