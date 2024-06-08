<?php

namespace App\Http\Resources\Admin\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class LetterResource  extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'user' => $this->user->name . ' ' . $this->user->lastname,
            'ten_name' => $this->tenant->name . ' ' . $this->tenant->lastname,
            'ten_dni' => $this->tenant->dni,
            'inm_title' => $this->immovable->title,
            'inm_address' => $this->immovable->address->street . ', ' . $this->immovable->address->city . ', ' . $this->immovable->address->neighborhood,
            'status' => $this->status == 'available' ? 'Disponible' : 'No disponible',
            'file_path' => $this->file_path,
            'created_at' => $this->created_at,
        ];
    }
}
