<?php

namespace App\Models\Contracts;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminContract extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'admin_contracts';
    protected $fillable = [
        'immovable_id',
        'owner_id',
        'start_date',
        'end_date',
        'status',
    ];
}
