<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class InventoryConsumableMaterial extends Model
{
    use HasFactory, HasUuids;

    protected $table = 'inventory_consumable_materials';

    protected $fillable = [
        'service_order_id',
        'material_id',
        'material_type',
        'qty',
    ];


    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id', 'id');
    }
}
