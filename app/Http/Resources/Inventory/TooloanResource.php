<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\JsonResource;

class TooloanResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'service' => $this->serviceOrder?->code ?? 'Sin orden de servicio',
            'loanded' => $this->user?->name . ' ' . $this->user?->lastname,
            'assigned' => $this->assigned?->name .  ' ' . $this->assigned?->lastname,
            'loan_date' => $this->loan_date,
            'expected_return_date' => $this->expected_return_date,
            'actual_return_date' => $this->actual_return_date,
            'status' => $this->status,
            'notes' => $this->notes,
        ];
    }
}
