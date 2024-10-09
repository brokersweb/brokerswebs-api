<?php

namespace App\Http\Resources\Inventory\Orders;

use Illuminate\Http\Resources\Json\JsonResource;

class ServiceDetailResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'description' => $this->description,
            'price' => $this->price,
        ];
    }
}
