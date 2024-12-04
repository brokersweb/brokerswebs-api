<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class InventoryClient extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'clients';

    protected $fillable = [
        'name',
        'phone',
        'rut',
        'nit',
        'dni',
        'email',
        'address',
        'status',
        'photo'
    ];
}
