<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class InventorySale extends Model
{
    use HasFactory,HasUuids;

    protected $table = 'sales';

    protected $fillable = [
        'client_id',
        'user_id',
        'tenant_id',
        'immovable_id',
        'serial',
        'total',
        'observation',
        'status',
    ];
}
