<?php

namespace App\Http\Resources\Admin\Owner;

use App\Models\Immovable;
use Illuminate\Http\Resources\Json\JsonResource;

class OwnerResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'dni' => $this->dni,
            'fullname' => $this->getFullNameAttribute(),
            'email' => $this->email,
            'phones' => $this->cellphone . ' / ' . $this->phone,
            'nimmovables' => Immovable::whereHas('owner', function ($query) {
                $query->where('owner_id', $this->id);
            })->count(),
            'created_at' => $this->getCreatedAt(),
        ];
    }
}
