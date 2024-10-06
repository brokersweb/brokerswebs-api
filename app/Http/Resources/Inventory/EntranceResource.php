<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\JsonResource;

class EntranceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'created_by' => $this->user?->name . ' ' . $this->user->lastname,
            'supplier' => $this->supplier?->name . ' ' . $this->supplier->lastname,
            'status' => $this->status(),
            'invoice' => $this->invoice,
            'voucher' => $this->voucher,
            'confirmed_at' => $this->confirmed_at,
            'cancelled_at' => $this->cancelled_at,
            'created_at' => $this->created_at,
        ];
    }
}
