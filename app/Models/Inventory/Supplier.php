<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Supplier extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'nit',
        'contact_person',
        'address',
        'phone',
        'email',
        'photo',
        'status'
    ];
}
