<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\JsonResource;

class MaterialStockOptionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->material->id,
            'name' => $this->material->name,
            'code' => $this->material->code,
        ];
    }
}
