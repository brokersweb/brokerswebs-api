<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AttendanceResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'attdate' => $this->attdate,
            'status' => $this->status,
            'notes' => $this->notes,
            'user' => $this->user->name . ' ' . $this->user->lastname,
            'confirmed' => $this->confirmed?->name . ' ' . $this->confirmed?->lastname,
            'created_at' => $this->created_at,
        ];
    }
}
