<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ImmovableOperationResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'title' => $this->title,
            'inumber' => $this->immonumber,
            'coownership' => $this->coownership?->name,
            'full_address' => $this->address->street . ', ' . $this->address->city . ', ' . $this->address->municipality,
            'owner_name' => $this->owner?->name . ' ' . $this->owner?->lastname,
            'owner_phone' => $this->owner?->phone . '-' . $this->owner?->cellphone,
            'tenant_name' => $this->tenant?->name . ' ' . $this->tenant?->lastname,
            'tenant_phone' => $this->tenant?->phone . ' - ' . $this->tenant?->cellphone,
        ];
    }
}
