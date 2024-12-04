<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class InventoryImage extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'entityable_type',
        'entityable_id',
        'situation',
        'url',
    ];


    public function entityable()
    {
        return $this->morphTo();
    }

}
