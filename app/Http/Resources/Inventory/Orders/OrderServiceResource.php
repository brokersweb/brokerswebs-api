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
            'staff' => [
                'fullname' => $this->staff?->name . ' ' . $this->staff->lastname,
                'phone' => $this->staff?->phone . ' - ' . $this->staff?->cellphone
            ],
            'comment' => $this->comment,
            'status' => $this->status(),
            'tenant' => [
                'id' => $this->client->tenant?->id,
                'fullname' => $this->client?->tenant?->name . ' ' . $this->client?->tenant?->lastname,
                'phone' => $this->client?->tenant?->phone . ' ' . $this->client?->tenant?->cellphone,

            ],
            'immovable' => [
                'id' => $this->client->id,
                'status' => $this->client?->status,
                'code' => $this->client?->code,
                'title' => $this->client?->title,
                'address' => $this->client?->address?->street . ' ' . $this->client?->address?->city . ', ' .  $this->client?->address?->neighborhood,
            ],
            'services' => ServiceDetailResource::collection($this->whenLoaded('services')),
            'start_date' => $this->start_date,
            'start_time' => $this->start_time,
            'created_at' => $this->created_at,
        ];
    }
}
