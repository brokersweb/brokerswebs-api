<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountStatusPdfResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'immovable_code' => $this->immovable?->code,
            'immovable_street' => $this->immovable?->address->street . '.',
            'immovable_city' => $this->immovable?->address->city . ', ' . $this->immovable?->address->neighborhood . '.',
            'start_contract' => '2020',
            'owner_name' => $this->owner?->name . ' ' . $this->owner?->last_name,
            'owner_email' => $this->owner?->email,
            'owner_phone' => $this->owner?->cellphone . ' ' . $this->owner?->phone,
            'owner_dni' => $this->owner?->dni,
        ];
    }
}
