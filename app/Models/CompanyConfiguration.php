<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class CompanyConfiguration extends Model
{
    use HasUuids, HasFactory;
    protected $table = 'company_configurations';

    protected $fillable = [
        'name', 'email', 'cellphone', 'logo', 'phone', 'nit', 'city', 'department', 'address'
    ];

    protected $hidden = [
        'updated_at',
        'created_at'
    ];
}
