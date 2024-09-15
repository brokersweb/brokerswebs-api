<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class ServiceOrder extends Model
{
    use HasFactory,HasUuids;

    protected $fillable = [
        'order_number',
        'user_id',
        'assigned_id',
        'client_type',
        'client_id',
        'status',
        'description',
        'start_date',
        'start_time',
        'type',
        'location',
        'client_phone'
    ];
}
