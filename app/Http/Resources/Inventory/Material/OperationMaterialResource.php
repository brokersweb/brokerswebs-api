<?php

namespace App\Http\Resources\Inventory\Material;

use Illuminate\Http\Resources\Json\JsonResource;

class OperationMaterialResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => $this->user->name . ' ' . $this->user->lastname,
            'info' => json_decode($this->info),
            'code' => $this->code,
            'created_at' => $this->created_at,
        ];
    }
}
