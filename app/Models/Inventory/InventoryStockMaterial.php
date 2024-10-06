<?php

namespace App\Models\Inventory;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use OwenIt\Auditing\Contracts\Auditable;

class InventoryStockMaterial extends Model
{
    use HasFactory, HasUuids;


    protected $table = 'inventory_stock_materials';

    protected $fillable = [
        'user_id',
        'owner_id',
        'material_id',
        'qty',
        'price',
        'total',
    ];



    public function material()
    {
        return $this->belongsTo(Material::class, 'material_id');
    }

    public function  staff()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }
}
