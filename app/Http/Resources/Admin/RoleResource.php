<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class RoleResource extends JsonResource
{
    public function toArray($request)
    {
        $name = $this->name;

        if ($name == 'Administrator') {
            $name = 'Administrador';
        } else if ($name == 'Lessor') {
            $name = 'Propietario';
        } else if ($name == 'Tenant') {
            $name = 'Inquilino';
        } else {
            $name = 'Invitado';
        }

        return [
            'id' => $this->id,
            'name' => $name
        ];
    }
}
