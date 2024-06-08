<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ImmovableResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'code' => $this->code,
            'main_image' => $this->main_image,
            'sale_price' => $this->sale_price,
            'rent_price' => $this->rent_price,
            'category' => $this->category,
            'full_address' => $this->address->city . ', ' . $this->address->municipality . ', ' . $this->address->country,
            'bathrooms' => $this->details?->bathrooms ?? 0,
            'bedrooms' => $this->details?->bedrooms ?? 0,
            'parkings' => $this->details?->hasparkings == 'Si' ? 1 : 0,
            'total_area' => $this->details?->total_area ?? 0,
            'description' => $this->description,
            'type' => $this->immovableType?->description,
            'is_favorite' => $this->favorites->contains('user_id', Auth::id()),
        ];
    }
}
