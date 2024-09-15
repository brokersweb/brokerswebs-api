<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class InventoryMovement extends Model
{
    use HasFactory,HasUuids;

    protected $fillable = [
        'reference',
        'type',
        'supplier_id',
        'status',
        'user_id',
        'order_service_id',
        'approved_at',
        'completed_at',
        'rejected_at',
        'notes',
    ];
}
