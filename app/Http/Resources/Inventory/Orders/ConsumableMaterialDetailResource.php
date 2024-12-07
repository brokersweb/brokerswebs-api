<?php

namespace App\Http\Resources\Inventory\Orders;

use Illuminate\Http\Resources\Json\JsonResource;

class ConsumableMaterialDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->material->name,
            'qty' => $this->qty,
            'price' => intval($this->material->price_basic),
            'total' => $this->qty * $this->material->price_basic
        ];
    }
}
