<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\JsonResource;

class ToolResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'code' => $this->code,
            'total_quantity' => $this->total_quantity,
            'available_quantity' => $this->available_quantity,
            'photo' => $this->photo,
            'category' => $this->category?->name,
            'status' => $this->status(),
            'created_at' => $this->created_at,
        ];
    }
}
