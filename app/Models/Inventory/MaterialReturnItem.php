<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class MaterialReturnItem extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'material_return_id',
        'material_id',
        'qty',
    ];
}
