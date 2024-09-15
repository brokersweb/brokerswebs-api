<?php

namespace App\Http\Resources\Admin\Coownership;

use App\Models\Immovable;
use Illuminate\Http\Resources\Json\JsonResource;

class CoownershipResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'qtyInmovable' => Immovable::where('co_ownership_id', $this->id)->where('status', 'rented')->count(),
        ];
    }
}
