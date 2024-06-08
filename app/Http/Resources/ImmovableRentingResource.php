<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Facades\Auth;

class ImmovableRentingResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'code' => $this->code,
            'avatar' => $this->main_image,
            'rent_price' => $this->rent_price,
            'category' => $this->category,
            'bathrooms' => $this->details?->bathrooms ?? 0,
            'bedrooms' => $this->details?->bedrooms ?? 0,
            'parkings' => $this->details?->hasparkings == 'Si' ? 1 : 0,
            'total_area' => $this->details?->total_area ?? 0,
            'type' => $this->immovableType?->description
        ];
    }
}
