<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class ImmovableAdminResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'code' => $this->code,
            'enrollment' => $this->enrollment,
            'type' => $this->immovableType?->description,
            'city' => $this->address?->city,
            'sector' => $this->address?->neighborhood,
            'street' => $this->address?->street,
            'coownership' => $this->coownership?->name ?  $this->coownership?->name : 'No asignada',
            'total_area' => $this->details?->total_area ?? 0,
            'gross_area' => $this->details?->gross_building_area ?? 0,
            'sale_price' => $this->sale_price,
            'rent_price' => $this->rent_price,
            'category' => $this->category,
            'owner_holder' => $this->owner?->dni ?? 'No asignado',
            'created_at' => $this->created_at ? $this->created_at->format('d-m-Y') : null,
            'status' => $this->status,
            'hasTenant' => $this->hasTenant(),
        ];
    }
}
