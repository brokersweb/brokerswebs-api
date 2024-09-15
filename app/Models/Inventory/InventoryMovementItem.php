<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class InventoryMovementItem extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'inventory_movement_id',
        'item',
        'qty',
        'price',
    ];
}
