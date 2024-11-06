<?php

namespace App\Http\Resources\Admin\Billing;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountCollectionResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'immovable' => $this->immovable?->title,
            'immovable_code' => $this->immovable?->code,
            'immovable_coownership' => $this->immovable?->co_ownership_name,
            'tenant' => $this->tenant?->where('type', 'holder')->first()?->name . ' ' . $this->tenant?->where('type', 'holder')->first()?->lastname,
            'contract_number' => $this->contract_number,
            'month' => $this->month,
            'year' => $this->year,
            'status' => $this->getStatus($this->status),
            'created_at' => $this->created_at ? $this->created_at->format('d-m-Y') : null,
        ];
    }

}
