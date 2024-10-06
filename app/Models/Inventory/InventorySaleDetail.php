<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class InventorySaleDetail extends Model
{
    use HasFactory,HasUuids;

    protected $table = 'inventory_sale_details';

    protected $fillable = [
        'inventory_sale_id',
        'material_id',
        'material_type',
        'qty',
        'price',
        'total',
    ];
}
