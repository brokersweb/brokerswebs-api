<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\JsonResource;

class PurchaseResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'serial' => $this->serial,
            'user' => $this->user?->name . ' ' . $this->user?->lastname,
            'user_approved' => $this->user?->name . ' ' . $this->user?->lastname,
            'supplier' => $this->supplier?->name,
            'observation' => $this->observation,
            'total' => $this->total,
            'status' => $this->status(),
            'created_at' => $this->created_at,
        ];
    }
}
