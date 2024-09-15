<?php

namespace App\Models\Inventory;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Tool extends Model
{
    use HasFactory, HasUuids;

    protected $fillable = [
        'name',
        'code',
        'total_quantity',
        'available_quantity',
        'category_id',
        'photo',
        'status'
    ];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function toolLoans()
    {
        return $this->hasMany(ToolLoan::class);
    }

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
