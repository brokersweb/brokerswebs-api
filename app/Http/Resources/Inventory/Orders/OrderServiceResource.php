<?php

namespace App\Http\Resources\Inventory\Orders;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderServiceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'created_by' => $this->user?->name . ' ' . $this->user->lastname,
            'progress' => $this->progress . '%',
            'request' => $this->request,
            'staff' => [
                'fullname' => $this->staff?->name . ' ' . $this->staff->lastname,
                'phone' => $this->staff?->cellphone,
                // 'photo' => $this->staff?->photo
            ],
            'comment' => $this->notes,
            'status' => $this->status(),
            'client_type' => $this->type == 'int' ? 'Interno' : 'Externo',
            'client' => [
                'id' => $this->client?->id,
                'fullname' => $this->client?->name,
                'phone' => $this->client?->phone,
                'address' => $this->client?->address
            ],
            'tenant' => [
                'id' => $this->immovable->tenant?->id,
                'fullname' => $this->immovable->tenant?->name . ' ' . $this->immovable->tenant?->lastname,
                'phone' => $this->immovable->tenant?->phone . ' ' . $this->immovable->tenant?->cellphone,
            ],
            'immovable' => [
                'id' => $this->immovable->id,
                'status' => $this->immovable?->status,
                'code' => $this->immovable?->code,
                'title' => $this->immovable?->title,
                'address' => $this->immovable?->address?->street . ' ' . $this->immovable?->address?->city . ', ' .  $this->immovable?->address?->neighborhood,
            ],
            'services' => ServiceDetailResource::collection($this->whenLoaded('services')),
            'materials' => ConsumableMaterialDetailResource::collection($this->whenLoaded('consumes')),
            'start_date' => $this->start_date,
            'start_time' => $this->start_time,
            'created_at' => $this->created_at,
        ];
    }
}
