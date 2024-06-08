<?php

namespace App\Http\Resources\Admin\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class ImmovableLetterResource  extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'coownership' => $this->co_ownership_name,
            'type' => $this->immovableType?->description,
            'immonumber' => $this->immonumber ?? 01,
            'address' => $this->address->street . ', ' . $this->address->city . ', ' . $this->address->neighborhood,
        ];
    }
}
