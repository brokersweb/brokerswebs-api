<?php

namespace App\Models\Base;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Readjustment extends Model
{
    use HasUuids, HasFactory;

    protected $table = 'readjustments';

    protected $fillable = [
        'residential_unit',
        'apt_no',
        'tenant_name',
        'date_visit',
        'worth',
        'start_contract',
        'end_contract',
        'phone',
        'owner_name',
        'phone_two',
        'readjustment',
        'status'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
