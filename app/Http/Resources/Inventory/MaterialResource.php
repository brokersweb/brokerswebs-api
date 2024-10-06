<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\JsonResource;

class MaterialResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'stock' => $this->stock,
            'unit' => $this->unit,
            'photo' => $this->photo,
            'price_basic' => $this->price_basic,
            'status' => $this->status(),
            'created_at' => $this->created_at,
        ];
    }
}
