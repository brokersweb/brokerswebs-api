<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class InventorySale extends Model
{
    use HasFactory,HasUuids;

    protected $fillable = [
        'inventory_client_id',
        'user_id',
        'serial',
        'total',
        'observation',
        'sale_date'
    ];
}
