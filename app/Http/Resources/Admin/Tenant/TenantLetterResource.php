<?php

namespace App\Http\Resources\Admin\Tenant;

use App\Models\Tenant;
use Illuminate\Http\Resources\Json\JsonResource;

class TenantLetterResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name . ' ' . $this->lastname,
            'dni' => $this->dni,
            'gender' => $this->gender,
            'exp_place' => $this->expedition_place,
            'immovables' => $this->immovables->map(function ($immovable) {
                return [
                    'id' => $immovable->id,
                    'address' => $immovable->address->street . ', ' . $immovable->address->city . ', ' . $immovable->address->neighborhood,
                    'title' => $immovable->title,
                    'anumber' => $immovable->immonumber == null ? 01 : $immovable->immonumber,
                    'coownership' => $immovable->co_ownership_name
                ];
            }),

        ];
    }
}
