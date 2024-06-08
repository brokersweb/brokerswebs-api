<?php

namespace App\Http\Resources\Admin\Billing;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountCollectionPdfResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'immovable_code' => $this->immovable?->code,
            'immovable_street' => $this->immovable?->address->street . '.',
            'immovable_city' => $this->immovable?->address->city . ', ' . $this->immovable?->address->neighborhood . '.',
            'start_contract' => '2023',
            'tenant_name' => $this->tenant?->where('type', 'holder')->first()?->name . ' ' . $this->tenant?->where('type', 'holder')->first()?->last_name,
            'tenant_email' => $this->tenant?->where('type', 'holder')->first()?->email ?? '',
            'tenant_phone' => $this->tenant?->where('type', 'holder')->first()?->cellphone,
            'tenant_dni' => $this->tenant?->where('type', 'holder')->first()?->dni,
        ];
    }
}
