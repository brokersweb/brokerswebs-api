<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Material extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'code',
        'stock',
        'unit',
        'price_basic',
        'photo',
        'status'
    ];

    public function status()
    {
        switch ($this->status) {

            case 'out_stock':
                return 'Sin stock';
                break;

            case 'unavailable':
                return 'No disponible';
                break;

            default:
                return 'Disponible';
                break;
        }
    }
}
