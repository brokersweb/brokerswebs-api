<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

class AccountStatusSelectResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'code' => $this->code,
            'coadminvalue' => $this->co_adminvalue,
            'cownership' => $this->co_ownership_name,
            'rentprice' => $this->rent_price,
            'balance' => $this->balance,
        ];
    }
}
