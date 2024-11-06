<?php

namespace App\Http\Resources\Inventory\Tool;

use Illuminate\Http\Resources\Json\JsonResource;

class TooloanResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'loan_date' => $this->loan_date,
            'expected_return_date' => $this->expected_return_date,
            'actual_return_date' => $this->actual_return_date,
            'status' => $this->status,
            'notes' => $this->notes,
        ];
    }
}
