<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class InventorySaleDetail extends Model
{
    use HasFactory,HasUuids;

    protected $table = 'sale_details';

    protected $fillable = [
        'sale_id',
        'product_id',
        'product_type',
        'qty',
        'price',
        'total',
    ];
}
