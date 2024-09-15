<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MaterialReturn extends Model
{
    use HasFactory, HasUuids;
    
    protected $fillable = [
        'return_number',
        'user_id',
        'user_return_id',
        'service_order_id',
        'return_date',
        'notes',
    ];
}
