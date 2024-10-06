<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InventoryEntranceItem extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'inventory_entrance_id',
        'code',
        'name',
        'price',
        'qty',
        'total',
        'type'
    ];
}
