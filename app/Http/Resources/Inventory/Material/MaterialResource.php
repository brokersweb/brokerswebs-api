<?php

namespace App\Http\Resources\Inventory\Material;

use Illuminate\Http\Resources\Json\JsonResource;

class MaterialResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->material->name,
            'code' => $this->material->code,
            'qty' => $this->qty
        ];
    }
}
