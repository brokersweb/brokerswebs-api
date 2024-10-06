<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ServiceOrderDetail extends Model
{
    use HasFactory, HasUuids;

    protected $table = "service_order_details";

    protected $fillable = [
        'service_order_id',
        'description',
        'qty',
        'price'
    ];
}
