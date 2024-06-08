<?php

namespace App\Http\Resources\Admin\Billing;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountCollectionSelectResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'tenant_name' => $this->tenants?->where('type', 'holder')->first()?->name . ' ' . $this->tenants?->where('type', 'holder')->first()?->last_name,
            'tenant_dni' => $this->tenants?->where('type', 'holder')->first()?->dni,
            'code' => $this->code,
            'cownership' => $this->co_ownership_name,
            'rentprice' => $this->rent_price,
        ];
    }
}
