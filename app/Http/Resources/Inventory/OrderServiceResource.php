<?php

namespace App\Http\Resources\Inventory;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderServiceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'created_by' => $this->user?->name . ' ' . $this->user->lastname,
            'staff' => $this->staff?->name . ' ' . $this->staff->lastname,
            'comment' => $this->comment,
            'status' => $this->status(),
            'client' => $this->client?->code . ', ' . $this->client?->title,
            'start_date' => $this->start_date,
            'start_time' => $this->start_time,
            'created_at' => $this->created_at,
        ];
    }
}
