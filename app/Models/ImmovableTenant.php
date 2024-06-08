<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ImmovableTenant extends Model
{
    use HasUuids, HasFactory;
    protected $table = 'immovable_tenant';
    protected $fillable = [
        'immovable_id',
        'tenant_id',
    ];
}
