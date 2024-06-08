<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountStatusResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'immovable' => $this->immovable?->title,
            'immovable_code' => $this->immovable?->code,
            'immovable_coownership' => $this->immovable?->co_ownership_name,
            'owner' => $this->owner?->name . ' ' . $this->owner?->last_name,
            'contract_number' => $this->contract_number,
            'month' => $this->month,
            'year' => $this->year,
            'status' => $this->status === 'Pending' ? 'Pendiente' : 'Pagado',
            'created_at' => $this->created_at ? $this->created_at->format('d-m-Y') : null,
        ];
    }
}
